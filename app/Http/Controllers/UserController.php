<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserController extends Controller
{
    /**
     * Load page (Blade)
     */
    public function index()
    {
        $activeUsers = User::with('office')
            ->where('status', 'active')
            ->get();

        $requestUsers = User::with('office')
            ->where('status', 'inactive') // pending requests
            ->get();

        $pos = PurchaseOrder::orderByRaw('CASE WHEN pdf_po IS NULL OR pdf_po = "" THEN 0 ELSE 1 END')
            ->latest()
            ->get();

        return view('users.index', compact('activeUsers', 'requestUsers', 'pos'));
    }

    /**
     * API: Get users for modal
     */
public function fetchUsers()
{
    return response()->json([
        'active' => $this->activeUsersQuery()
            ->where('role', '!=', 'presentative_staff')
            ->get(),

        'requests' => $this->requestUsersQuery()
            ->where('role', '!=', 'presentative_staff')
            ->get(),

        'rejected' => $this->rejectedUsersQuery()
            ->where('role', '!=', 'presentative_staff')
            ->get(),
    ]);
}

    /**
     * Approve user
     */
    public function approve($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'status' => 'active'
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Reject user
     */
    public function reject($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'status' => 'rejected'
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Update user status (AJAX)
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:active,freeze,inactive,rejected'
            ]);

            $user = User::findOrFail($id);

            $user->update([
                'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'status' => $user->status
            ]);

        } catch (\Throwable $e) {

            Log::error('updateStatus error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update status'
            ], 500);
        }
    }

    /**
     * Update profile info
     */
    public function update(Request $request, User $user)
    {
        try {
            /** @var \App\Models\User|null $authUser */
            $authUser = auth()->user();
            if (!$authUser || ($authUser->id !== $user->id && !$authUser->isAdmin())) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized.'
                ], 403);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'designation' => 'required|string|max:255',
                'email' => 'required|email',
                'signature' => 'nullable|image|mimes:png,jpg,jpeg|max:4096',
                'authorization_letter' => 'nullable|file|mimes:pdf|max:5120',
            ]);

            /*
            |--------------------------------------------------------------------------
            | HANDLE SIGNATURE
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('signature')) {

                /*
                |--------------------------------------------------------------------------
                | DELETE OLD SIGNATURE
                |--------------------------------------------------------------------------
                */

                if (
                    $user->signature &&
                    File::exists(
                        storage_path('app/private/' . $user->signature)
                    )
                ) {
                    File::delete(
                        storage_path('app/private/' . $user->signature)
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | CREATE NEW FILE
                |--------------------------------------------------------------------------
                */

                $file = $request->file('signature');
                $filename = (string) Str::uuid() . '.png';

                $file->move(
                    storage_path('app/private/signatures'),
                    $filename
                );

                $validated['signature'] = 'signatures/' . $filename;
            }

            /*
            |--------------------------------------------------------------------------
            | HANDLE AUTHORIZATION LETTER (PDF)
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('authorization_letter')) {
                $file = $request->file('authorization_letter');

                $scanner = new \App\Services\FileSecurityScanner();
                $scanResult = $scanner->scanUploadedFile($file);
                if (!$scanResult['safe']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Security Threat Blocked: ' . $scanResult['reason']
                    ], 422);
                }

                // Delete old authorization letter file if it exists
                if ($user->authorization_letter) {
                    if (Storage::disk('private')->exists($user->authorization_letter)) {
                        Storage::disk('private')->delete($user->authorization_letter);
                    } elseif (File::exists(storage_path('app/private/' . $user->authorization_letter))) {
                        File::delete(storage_path('app/private/' . $user->authorization_letter));
                    }
                }

                $fileName = Str::uuid() . '.' . strtolower($file->getClientOriginalExtension());
                $filePath = $file->storeAs('authorization_letters', $fileName, 'private');

                $validated['authorization_letter'] = $filePath;
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE USER
            |--------------------------------------------------------------------------
            */

            $user->update([
                'name' => $validated['name'],
                'designation' => $validated['designation'],
                'email' => $validated['email'],
                'signature' => $validated['signature'] ?? $user->signature,
                'authorization_letter' => $validated['authorization_letter'] ?? $user->authorization_letter,
            ]);

            return response()->json([
                'success' => true,
                'signature' => $user->signature,
                'authorization_letter' => $user->authorization_letter,
                'authorization_letter_url' => $user->authorization_letter ? route('authorization.letter', $user->authorization_letter_token) : null,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first() ?? 'Validation error.';
            return response()->json([
                'success' => false,
                'message' => $firstError,
                'errors' => $e->errors()
            ], 422);
        } catch (\Throwable $e) {

        Log::error('PROFILE UPDATE ERROR', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Count pending users
     */
    public function countPendingUsers(Request $request)
    {
        $authUser = $request->user();

        $query = $this->requestUsersQuery();

        // restrict non-admin users
        if (!$authUser->isAdmin()) {
            $query->where('department', $authUser->department);
        }

        return response()->json([
            'total' => $query->count()
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Reusable Query Helpers
    |--------------------------------------------------------------------------
    */

public function signature(User $user)
{
    if (!$user->signature) {
        abort(404);
    }

    $path = storage_path('app/private/' . $user->signature);

    if (!File::exists($path)) {
        abort(404);
    }

    return response()->file($path);
}

    private function activeUsersQuery()
    {
        return User::with('office')
            ->where('status', 'active');
    }

    private function requestUsersQuery()
    {
        return User::with('office')
            ->where('status', 'inactive');
    }

    private function rejectedUsersQuery()
    {
        return User::with('office')
            ->whereIn('status', ['rejected', 'freeze', 'inactive', 'disabled', 'suspended']);
    }


public function fetchAuthorizationUsers()
{
    $users = User::with('office')
        ->where('role', 'presentative_staff')
        ->get()
        ->map(function ($user) {

            $user->authorization_letter_url = $user->authorization_letter
                ? route('authorization.letter', $user->authorization_letter_token)
                : null;

            return $user;
        });

    return response()->json([
        'pending' => $users->where('status', 'inactive')->values(),
        'active' => $users->where('status', 'active')->values(),
        'rejected' => $users->whereIn('status', ['rejected', 'freeze', 'inactive', 'disabled', 'suspended'])->values(),
    ]);
}

public function downloadAuthorizationLetter($token)
{
    /** @var \App\Models\User|null $authUser */
    $authUser = auth()->user();
    if (!$authUser) {
        abort(403);
    }

    $user = User::findByAuthLetterIdentifier((string) $token);

    if (!$user) {
        abort(404);
    }

    // Allow user to view their own letter or admins to view any letter
    if ($authUser->id !== $user->id && !$authUser->isAdmin()) {
        abort(403);
    }

    if (!$user->authorization_letter) {
        abort(404);
    }

    $path = $user->authorization_letter;

    if (!Storage::disk('private')->exists($path)) {
        abort(404);
    }

    $fullPath = storage_path('app/private/' . $path);

    return response()->file($fullPath, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="authorization_letter_' . $user->authorization_letter_token . '.pdf"'
    ]);
}

    /**
     * Change authenticated user password
     */
    public function changePassword(Request $request)
    {
        try {
            /** @var \App\Models\User $user */
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated.'
                ], 401);
            }

            $validated = $request->validate([
                'current_password' => ['required', 'string'],
                'new_password' => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
            ], [
                'current_password.required' => 'Please enter your current password.',
                'new_password.required' => 'Please enter a new password.',
                'new_password.min' => 'The new password must be at least 8 characters long.',
                'new_password.confirmed' => 'The new password confirmation does not match.',
                'new_password.different' => 'The new password must be different from your current password.',
            ]);

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'The current password you provided is incorrect.'
                ], 422);
            }

            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully.'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first() ?? 'Validation error.';
            return response()->json([
                'success' => false,
                'message' => $firstError,
                'errors' => $e->errors()
            ], 422);
        } catch (\Throwable $e) {
            Log::error('CHANGE PASSWORD ERROR', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while changing password.'
            ], 500);
        }
    }

}
