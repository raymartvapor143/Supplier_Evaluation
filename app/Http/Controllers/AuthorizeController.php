<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Authorize;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Pdf;



class AuthorizeController extends Controller
{

public function upload(Request $request)
{
    $request->validate([
        'pdf_file' => 'required|file|mimes:pdf|max:5120',
    ]);

    try {

        $user = auth()->user();

        if (!$user) {
            throw new \Exception('User not authenticated.');
        }

        $file = $request->file('pdf_file');

        $folder = 'pdf_files';

        $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());

        // Store file
        $path = $file->storeAs($folder, $filename, 'private');


        $path = ltrim($path, '/');

        // Check existing PDF of the user
        $existing = Pdf::where('user_id', $user->id)->first();

        if ($existing) {


            if ($existing->pdf_file) {

                $oldPath = ltrim($existing->pdf_file, '/');

            if (Storage::disk('private')->exists($oldPath)) {
                Storage::disk('private')->delete($oldPath);
            }
            }


            $existing->update([
                'pdf_file' => $path,
                'status'   => 'pending',
            ]);

        } else {

            // Create new record
            Pdf::create([
                'user_id'  => $user->id,
                'pdf_file' => $path,
                'status'   => 'pending',
            ]);
        }

        return back()->with('success', 'PDF uploaded successfully.');

    } catch (\Exception $e) {

        return back()->with('error', $e->getMessage());
    }
}


public function viewPdf($filename)
{
    $user = auth()->user();

    if (!$user) {
        abort(403);
    }

    $path = 'pdf_files/' . $filename;

    if (!Storage::disk('private')->exists($path)) {
        abort(404);
    }



    $fullPath = storage_path('app/private/' . $path);

    if (!file_exists($fullPath)) {
        abort(404);
    }

    return response()->file($fullPath, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="'.$filename.'"'
    ]);
}

public function fetchPdfData()
{
    $user = auth()->user();

    if (! $user) {
        return response()->json([], 401);
    }

    $pdfsQuery = Pdf::with(['user.office']);


    /** @var User $user */
    if ($user->isAdmin()) {

        $pdfs = $pdfsQuery->latest()->get();

    }

    else {

        $pdfs = $pdfsQuery
            ->whereHas('user', function ($q) use ($user) {
                $q->where('office_id', $user->office_id);
            })
            ->latest()
            ->get();
    }


    $pdfs = $pdfs->map(function ($pdf) {
        return [
            'id' => $pdf->id,
            'user' => $pdf->user,
            'status' => $pdf->status,
            'updated_at' => $pdf->updated_at,


            'pdf_file' => route('secure.pdf', [
                'filename' => basename($pdf->pdf_file)
            ]),
        ];
    });

    return response()->json($pdfs);
}


public function updateStatus($id)
{
    $pdf = Pdf::findOrFail($id);
    $data = request()->validate([
        'status' => 'required|in:approved,rejected',
    ]);

    $pdf->update($data);

    return response()->json([
        'success' => true,
        'message' => "PDF status updated to {$pdf->status}.",
    ]);
}

public function getAllApprovedPdfs()
{
    $user = auth()->user();
     /** @var User $user */
    if (!$user->isAdmin()) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $pdfs = Pdf::with('user.office')
        ->where('status', 'approved')
        ->latest()
        ->get();

    return response()->json($pdfs);
}

public function getApprovedPdf()
{
    try {

        $user = auth()->user();

        $pdf = Pdf::where('user_id', $user->id)
            ->where('status', 'approved')
            ->latest()
            ->first();

        if (!$pdf) {
            return response()->json([
                'success' => false,
                'message' => 'No approved PDF found'
            ]);
        }

        return response()->json([
            'success' => true,
            'pdf' => [
                'id' => $pdf->id,
            'pdf_file' => route('secure.pdf', [
                'filename' => basename($pdf->pdf_file)
            ]),
            ]
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

// public function store(Request $request)
// {
//     try {

//         $user = auth()->user();

//         // =========================
//         // BLOCK ADMIN
//         // =========================
//           /** @var User $user */
//         if ($user->isAdmin()) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Administrators cannot attach authorization files.'
//             ], 403);
//         }

//         $request->validate([
//             'evaluation_id' => 'required|exists:evaluations,id',
//         ]);

//         // =========================
//         // GET APPROVED PDF (USER ONLY)
//         // =========================
//         $pdf = Pdf::where('user_id', $user->id)
//             ->where('status', 'approved')
//             ->latest()
//             ->first();

//         if (!$pdf) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'No approved authorization file found.'
//             ], 422);
//         }

//         // =========================
//         // UPSERT (NO DUPLICATES)
//         // =========================
//         Authorize::updateOrCreate(
//             [
//                 'user_id' => $user->id,
//                 'evaluation_id' => $request->evaluation_id,
//             ],
//             [
//                 'pdf_id' => $pdf->id,
//                 'office_id' => $user->office_id,
//             ]
//         );

//         return response()->json([
//             'success' => true,
//             'message' => 'Authorization attached successfully'
//         ]);

//     } catch (\Exception $e) {

//         return response()->json([
//             'success' => false,
//             'message' => $e->getMessage()
//         ], 500);
//     }
// }

public function store(Request $request)
{
    try {
        $user = auth()->user();


         /** @var User $user */
        if ($user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Administrators cannot attach authorization files.'
            ], 403);
        }

        $request->validate([
            'evaluation_id' => 'required|exists:evaluations,id',
        ]);


        $authorize = Authorize::where('evaluation_id', $request->evaluation_id)
            ->first();

        if ($authorize) {
            // Get PDF linked to this authorization
            $pdf = Pdf::where('id', $authorize->pdf_id)
                ->where('user_id', $authorize->user_id)
                ->first();

            if (!$pdf || !$pdf->pdf_file) {
                $button = $user->role === 'end_user' ? 'add' : 'hide';
            } else {
                $button = 'view';
            }

            return response()->json([
                'success' => true,
                'message' => 'Evaluation already authorized',
                'button' => $button,
                'pdf' => $pdf ? $pdf->pdf_file : null,
            ]);
        }


        $pdf = Pdf::where('user_id', $user->id)
            ->where('status', 'approved')
            ->latest()
            ->first();

        if (!$pdf) {
            return response()->json([
                'success' => false,
                'message' => 'No approved authorization file found.'
            ], 422);
        }


        Authorize::updateOrCreate(
            [
                'user_id' => $user->id,
                'evaluation_id' => $request->evaluation_id,
            ],
            [
                'pdf_id' => $pdf->id,
                'office_id' => $user->office_id,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Authorization attached successfully',
            'button' => 'view',
            'pdf' => $pdf->pdf_file
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}



public function getAuthorizationByEvaluation($evaluationId)
{
    $user = auth()->user();

    /** @var User $user */
    if (!$user || !$user->isEndUser()) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $authorization = Authorize::where('evaluation_id', $evaluationId)
        ->with(['pdf', 'user'])
        ->first();

    if (!$authorization) {
        return response()->json(['exists' => false]);
    }

    return response()->json([
        'exists' => true,
        'pdf_file' => $authorization->pdf
            ? route('secure.pdf', [
                'filename' => basename($authorization->pdf->pdf_file)
            ])
            : null,
        'user_name' => $authorization->user->name,
        'user_designation' => $authorization->user->designation,
    ]);
}


}
