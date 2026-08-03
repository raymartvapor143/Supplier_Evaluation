<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;
use App\Models\ActivityLog;

use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Support\Facades\Password;



class AuthController extends Controller
{
   public function login(){

    $offices = Office::where('name', '!=', 'PGSO-Warehouse')->get();
    return view('auth.login', compact('offices'));
   }


public function register(Request $request)
{

    $validator = Validator::make(
        $request->all(),
        [
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'office_id' => 'required|exists:offices,id',

            'role' => 'required|in:end_user,administrator,pgso,head,presentative_staff',

            'email' => 'required|email',

            'password' => [
                'required',
                'confirmed',
                PasswordRule::min(8)
            ],

            'signature' => [
                'required',
                'string',
                'starts_with:data:image/'
            ],

            'authorization_letter' => [
                Rule::requiredIf($request->role === 'presentative_staff'),
                'file',
                'mimes:pdf',
                'max:5120'
            ],
        ],
        [
            'signature.required' => 'Signature is required.',
            'signature.starts_with' => 'Invalid signature format.',
            'authorization_letter.required' => 'Authorization Letter is required for Presentative Staff.',
            'authorization_letter.mimes' => 'Authorization Letter must be PDF',
        ]
    );

    if ($validator->fails()) {
        return response()->json([
            'message' => $validator->errors()->first()
        ], 422);
    }


    $signature = $request->signature;

    preg_match('/^data:image\/(\w+);base64,/', $signature, $matches);

    if (!isset($matches[1])) {
        return response()->json([
            'message' => 'Invalid signature format.'
        ], 400);
    }

    $extension = strtolower($matches[1]);

    if (!in_array($extension, ['png', 'jpg', 'jpeg'])) {
        return response()->json([
            'message' => 'Only PNG, JPG, JPEG allowed.'
        ], 400);
    }

    $imageData = base64_decode(substr($signature, strpos($signature, ',') + 1));

    if ($imageData === false) {
        return response()->json([
            'message' => 'Signature decoding failed.'
        ], 400);
    }

    $fileName = Str::uuid() . '.' . $extension;
    $filePath = 'signatures/' . $fileName;

    try {
        Storage::disk('private')->put($filePath, $imageData);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to save signature.'
        ], 500);
    }

    $signaturePath = $filePath;


    $authorizationPath = null;

    if ($request->hasFile('authorization_letter')) {

        $file = $request->file('authorization_letter');

        $scanner = new \App\Services\FileSecurityScanner();
        $scanResult = $scanner->scanUploadedFile($file);
        if (!$scanResult['safe']) {
            return response()->json([
                'message' => 'Security Threat Blocked: ' . $scanResult['reason']
            ], 422);
        }

        $fileName = Str::uuid() . '.' . strtolower($file->getClientOriginalExtension());

        $filePath = $file->storeAs(
            'authorization_letters',
            $fileName,
            'private'
        );

        $authorizationPath = $filePath;
    }


    $existingUser = User::where('email', $request->email)->first();

    if ($existingUser) {

        if ($existingUser->status !== 'rejected') {
            return response()->json([
                'message' => 'This email is already registered and cannot be reused.'
            ], 422);
        }

        $existingUser->update([
            'name' => $request->name,
            'designation' => $request->designation,
            'office_id' => $request->office_id,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'status' => 'inactive',
            'signature' => $signaturePath,
            'authorization_letter' => $authorizationPath,
        ]);
        ActivityLog::create([
            'user_id' => $existingUser->id,
            'role' => $existingUser->role,
            'activity' => 'Account Resubmission',
            'description' => 'Rejected account was resubmitted.',
            'status' => 'success',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'message' => 'Account re-submitted successfully. Waiting for admin approval.'
        ]);
    }


    $user = User::create([
        'name' => $request->name,
        'designation' => $request->designation,
        'office_id' => $request->office_id,
        'role' => $request->role,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'status' => 'inactive',
        'signature' => $signaturePath,
        'authorization_letter' => $authorizationPath,
    ]);

    ActivityLog::create([
        'user_id' => $user->id,
        'role' => $user->role,
        'activity' => 'Account Registration',
        'description' => 'New account registration submitted.',
        'status' => 'success',
        'ip_address' => $request->ip(),
        'user_agent' => $request->userAgent(),
    ]);

    return response()->json([
        'message' => 'Account created successfully. Your account will remain pending until you submit a User Access Form to the Office of the Provincial Procurement Management Officer for approval.'
    ]);
}



public function loginControl(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => $validator->errors()->first()
        ], 422);
    }


    $systemUserId = 1;

    $key = Str::lower($request->email) . '|' . $request->ip();

    if (RateLimiter::tooManyAttempts($key, 5)) {
        return response()->json([
            'locked' => true,
            'seconds' => RateLimiter::availableIn($key),
            'message' => 'Too many login attempts.'
        ], 429);
    }

    $credentials = $request->only('email', 'password');

    $user = User::where('email', $request->email)->first();


    // EMAIL NOT FOUND (POSSIBLE BRUTE FORCE)

    if (!$user) {

        RateLimiter::hit($key, 60);
        $remaining = RateLimiter::remaining($key, 5);

        ActivityLog::create([
            'user_id' => $systemUserId,
            'role' => 'guest',
            'activity' => 'Failed Login (Unknown Email)',
            'description' => "Brute-force attempt using email: {$request->email}",
            'status' => 'failed',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'message' => "Invalid email or password. Remaining attempts: {$remaining}"
        ], 401);
    }


    switch ($user->status) {

        case 'rejected':
            return response()->json([
                'message' => 'Your account has been rejected.'
            ], 403);

        case 'freeze':
            return response()->json([
                'message' => 'Your account has been temporarily frozen.'
            ], 403);

        case 'inactive':

            ActivityLog::create([
                'user_id' => $user->id,
                'role' => $user->role,
                'activity' => 'Login Blocked',
                'description' => 'Account pending approval.',
                'status' => 'warning',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'message' => 'Your account is pending approval. Your account will remain pending until you submit a User Access Form to the Office of the Provincial Procurement Management Officer for approval.'
            ], 403);

        case 'active':
            break;

        default:

            ActivityLog::create([
                'user_id' => $user->id,
                'role' => $user->role,
                'activity' => 'Login Blocked',
                'description' => 'Invalid account status.',
                'status' => 'warning',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'message' => 'Invalid account status.'
            ], 403);
    }


    if (!Auth::attempt($credentials)) {

        RateLimiter::hit($key, 60);
        $remaining = RateLimiter::remaining($key, 5);

        ActivityLog::create([
            'user_id' => $user->id,
            'role' => $user->role,
            'activity' => 'Failed Login',
            'description' => 'Incorrect password.',
            'status' => 'failed',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'message' => "Invalid email or password. Remaining attempts: {$remaining}"
        ], 401);
    }


    RateLimiter::clear($key);
    $request->session()->regenerate();

    ActivityLog::create([
        'user_id' => $user->id,
        'role' => $user->role,
        'activity' => 'Login',
        'description' => 'User logged into the system.',
        'status' => 'success',
        'ip_address' => $request->ip(),
        'user_agent' => $request->userAgent(),
    ]);

    return response()->json([
        'message' => 'Login successful!',
        'user' => [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $user->role,
        ]
    ]);
}

public function loginStatus(Request $request)
{
    $email = $request->email;

    if (!$email) {
        return response()->json([
            'locked' => false
        ]);
    }

    $key = Str::lower($email) . '|' . $request->ip();

    return response()->json([
        'locked' => RateLimiter::tooManyAttempts($key, 5),
        'seconds' => RateLimiter::availableIn($key)
    ]);
}

public function logout(Request $request)
{
    Auth::logout();

    if (Auth::check()) {

        ActivityLog::create([
            'user_id' => Auth::id(),
            'role' => Auth::user()->role,
            'activity' => 'Logout',
            'description' => 'User logged out.',
            'status' => 'success',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

    }

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
}










public function forgotPassword(Request $request)
{
    $request->validate([
        'email' => ['required', 'email']
    ]);


    $user = User::where('email', $request->email)->first();


    if (!$user) {

        return response()->json([
            'message' => 'No account was found with that email address.'
        ], 404);

    }


    $status = Password::sendResetLink(
        $request->only('email')
    );


    if ($status === Password::RESET_LINK_SENT) {

        return response()->json([
            'message' => 'A password reset link has been sent to your email.'
        ]);

    }


    return response()->json([
        'message' => 'Unable to send password reset link.'
    ], 500);
}



public function resetPassword(Request $request)
{
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => [
            'required',
            'confirmed',
            'min:8'
        ],
    ]);


    $status = Password::reset(
        $request->only(
            'email',
            'password',
            'password_confirmation',
            'token'
        ),

        function ($user, $password) {

            $user->forceFill([
                'password' => Hash::make($password)
            ])->save();

        }
    );


    if ($status === Password::PASSWORD_RESET) {

        return redirect('/login')
            ->with('success', 'Your password has been reset successfully.');

    }


    return back()->withErrors([
        'email' => 'Invalid reset token or email address.'
    ]);
}
}
