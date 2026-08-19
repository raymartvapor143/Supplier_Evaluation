<?php

namespace App\Http\Controllers;

use App\Models\Authorize;
use App\Models\CriteriaScore;
use App\Models\DigitalApproval;
use App\Models\Evaluation;
use App\Models\EvaluationLink;
use App\Models\Office;
use App\Models\Pdf as PdfModel;
use App\Models\PurchaseOrder;
use App\Models\Requests;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\ActivityLog;




class EvaluationController extends Controller
{

public function evaluation(){
    return view('evaluation.evaluation', ['title' => 'Evaluations Page']);
}


public function storeRequest(Request $request)
{

    $request->validate([
        'po_no'        => 'required|string',
        'reason'       => 'required|string',
        'request_type' => 'required|in:update,delete',
        'requested_to' => 'nullable|exists:users,id',
    ]);


    $evaluation = Evaluation::where('po_no', $request->po_no)->first();

    if (!$evaluation) {
        return response()->json([
            'success' => false,
            'message' => 'PO Number not found.'
        ], 404);
    }


    $existingRequest = $evaluation->requests()
        ->whereIn('status', ['request', 'approved'])
        ->first();

    if ($existingRequest) {
        return response()->json([
            'success' => false,
            'message' => 'A request is already pending or approved for this evaluation.'
        ], 400);
    }


    $newRequest = Requests::create([
        'evaluation_id' => $evaluation->id,
        'user_id'       => auth()->id(),
        'requested_to'  => $request->requested_to,
        'request_type'  => $request->request_type,
        'reason'        => $request->reason,
        'status'        => 'request', // ✅ stays as request
        'request_date'  => now(),
    ]);


    ActivityLog::create([
        'user_id' => auth()->id(),
        'role' => auth()->user()->role,
        'activity' => ucfirst($request->request_type) . ' Request',
        'description' => "Created a {$request->request_type} request for PO No. {$evaluation->po_no}.",
        'status' => 'success',
        'ip_address' => $request->ip(),
        'user_agent' => $request->userAgent(),
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Request created successfully!',
        'data'    => $newRequest
    ]);
}



public function store(Request $request)
{
    DB::beginTransaction();

    try {
        /** @var User $user */
        $user = auth()->user();

        $isAdmin = $user->isAdmin();
        $isPgso = $user->isPgso();
        $isPrivileged = $isAdmin || $isPgso;

        foreach ($request->evaluations as $evalData) {

            $poNo = $evalData['po_no'] ?? null;


            if (empty($evalData['supplier_name'])) {
                throw new \Exception("Supplier name is required.");
            }

            if (empty($poNo)) {
                throw new \Exception("PO number is required.");
            }


            $existingActive = Evaluation::where('po_no', $poNo)
                ->where('status', '!=', 'deleted')
                ->exists();

            if ($existingActive) {
                throw new \Exception(
                    "The PO number {$poNo} already exists and is active. Please use a different PO number."
                );
            }

            ActivityLog::create([
                'user_id' => auth()->id(),
                'role' => auth()->user()->role,
                'activity' => 'Duplicate PO',
                'description' => "Attempted to create an evaluation with duplicate PO No. {$poNo}.",
                'status' => 'failed',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);


            try {
                $dateEvaluation = Carbon::parse($evalData['date_evaluation'])
                    ->timezone('Asia/Manila');
            } catch (\Exception $ex) {
                throw new \Exception("Invalid evaluation date format.");
            }


            $year = $evalData['year'] ?? null;

            if (!$year) {
                throw new \Exception("Covered period (year) is required.");
            }

            // Optional: basic sanity check
            if (!is_numeric($year) || $year < 2000 || $year > 2100) {
                throw new \Exception("Invalid year selected.");
            }


            $coveredPeriod = 'CY ' . $year;


            $criteriaIds = [1, 2, 3, 4];

            foreach ($criteriaIds as $criteriaId) {
                $criteriaData = collect($evalData['criteria'] ?? [])
                    ->firstWhere('criteria_id', $criteriaId);

                if (!$isPrivileged && empty($criteriaData['rating'])) {
                    throw new \Exception("All criteria ratings must be selected for non-admin users.");
                }
            }

            if (empty($evalData['office_id'])) {
                throw new \Exception("Office is required.");
            }


            $status = $isPrivileged
                ? 'pending'
                : (collect($evalData['criteria'] ?? [])
                    ->every(fn($c) => !empty($c['rating']))
                    ? 'head review'
                    : 'pending');


            $evaluation = Evaluation::create([
                'supplier_name'   => $evalData['supplier_name'],
                'po_no'           => $poNo,
                'date_evaluation' => $dateEvaluation,


                'covered_period'  => $coveredPeriod,


                'period_year'     => $year,

                'office_id'       => $evalData['office_id'],
                'status'          => $status,
            ]);


            foreach ($criteriaIds as $criteriaId) {
                $criteriaData = collect($evalData['criteria'] ?? [])
                    ->firstWhere('criteria_id', $criteriaId);

                CriteriaScore::create([
                    'evaluation_id' => $evaluation->id,
                    'criteria_id'   => $criteriaId,
                    'number_rating' => $criteriaData['rating'] ?? null,
                    'remarks'       => $criteriaData['remarks'] ?? null,
                ]);
            }


            $evaluator = $request->evaluator;

            if (!empty($evaluator)) {

                $imagePath = null;

                if (!empty($evaluator['image']) && str_contains($evaluator['image'], 'data:image')) {

                    $image = preg_replace('#^data:image/\w+;base64,#i', '', $evaluator['image']);
                    $image = str_replace(' ', '+', $image);
                    $imageData = base64_decode($image);

                    if ($imageData !== false) {

                        $fileName = Str::uuid() . '.png';

                        $folder = 'public/photos/photo_signature/';

                        Storage::put($folder . $fileName, $imageData);

                        $imagePath = '/storage/photos/photo_signature/' . $fileName;
                    }
                }

                DigitalApproval::create([
                    'evaluation_id' => $evaluation->id,
                    'full_name'     => $evaluator['full_name'] ?? null,
                    'designation'   => $evaluator['designation'] ?? null,
                    'role'          => 'Prepared by',
                    'image'         => $imagePath,
                ]);
            }


            EvaluationLink::firstOrCreate(
                ['evaluation_id' => $evaluation->id],
                [
                    'token'      => Str::uuid()->toString(),
                    'code'       => strtoupper(Str::random(6)),
                    'expires_at' => null,
                    'is_used'    => false,
                ]
            );
            ActivityLog::create([
                'user_id' => auth()->id(),
                'role' => auth()->user()->role,
                'activity' => 'Create Evaluation',
                'description' => "Created supplier evaluation for PO No. {$evaluation->po_no}.",
                'status' => 'success',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Evaluation submitted successfully!'
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 400);
    }
}


// public function show($id)
// {
//     /** @var \App\Models\User $user */
//     $user = auth()->user();

//     if ($user->isPgso()) {
//         abort(403, 'You are not allowed to view this evaluation.');
//     }

//     $evaluation = Evaluation::with([
//         'office',
//         'criteriaScores',
//         'digitalApprovals'
//     ])->findOrFail($id);

//     return response()->json([
//         'evaluation' => [
//             'id' => $evaluation->id,
//             'supplier_name' => $evaluation->supplier_name,
//             'po_no' => $evaluation->po_no,
//             'date_evaluation' => $evaluation->date_evaluation,
//             'covered_period' => $evaluation->covered_period,
//             'office_name' => $evaluation->office->name ?? null,
//             'criteria_scores' => $evaluation->criteriaScores,

//             // ===============================
//             // DIGITAL APPROVALS
//             // ===============================
//             'digital_approvals' => $evaluation->digitalApprovals->map(function ($d) {

//                 return [
//                     'full_name' => $d->full_name,
//                     'designation' => $d->designation,
//                     'image' => $d->image_url,
//                     'role' => $d->role,
//                 ];
//             }),
//         ]
//     ]);
// }

public function show($id)
{

    ActivityLog::create([
        'user_id' => auth()->id(),
        'role' => auth()->user()->role,
        'activity' => 'View Evaluation',
        'description' => "Viewed evaluation ID {$id}.",
        'status' => 'success',
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent(),
    ]);


    /** @var \App\Models\User $user */
    $user = auth()->user();

    if ($user->isPgso()) {
        abort(403, 'You are not allowed to view this evaluation.');
    }

    $evaluation = Evaluation::with([
        'office',
        'criteriaScores',
        'digitalApprovals.signer',
        'digitalApprovals.authorize.pdf',
    ])->findOrFail($id);

    $approvals = $evaluation->digitalApprovals;
    $office = $evaluation->office;


    $preparedBy = $approvals->firstWhere('role', 'Prepared by');

    $headApproval = $approvals->firstWhere('role', 'Head');

    $representativeApproval = $approvals->firstWhere('role', 'presentative_staff');



    $headUser = User::where('office_id', $office->id)
        ->where('role', 'head')
        ->first();

    if ($headApproval) {

        $headInfo = [
            'name' => $headApproval->full_name,
            'designation' => $headApproval->designation,
            'image' => $headApproval->signer
                ? route('signature', $headApproval->signer->id)
                : null,
            'created_at' => $headApproval->created_at,
            'source' => 'approval',
        ];

    } elseif ($headUser) {

        $headInfo = [
            'name' => $headUser->name,
            'designation' => $headUser->designation,
            'image' => $headUser->signature
                ? route('signature', $headUser->id)
                : null,
            'created_at' => null,
            'source' => 'user',
        ];

    } else {

        $headInfo = [
            'name' => $office->head ?? '-',
            'designation' => $office->designation ?? '-',
            'image' => null,
            'created_at' => null,
            'source' => 'office',
        ];
    }


    $representativeUser = $representativeApproval?->signer;


    $authorizeFile = null;

    if ($headApproval && $headApproval->authorize && $headApproval->authorize->pdf) {
        $authorizeFile = route('secure.pdf', [
            'filename' => basename($headApproval->authorize->pdf->pdf_file)
        ]);
    }

    return response()->json([


        'evaluation' => [
            'id' => $evaluation->id,
            'supplier_name' => $evaluation->supplier_name,
            'po_no' => $evaluation->po_no,
            'date_evaluation' => $evaluation->date_evaluation,
            'covered_period' => $evaluation->covered_period,
            'office_name' => $evaluation->office->name ?? null,
            'criteria_scores' => $evaluation->criteriaScores,
        ],


        'digital_approvals' => $approvals->map(function ($d) {

            return [
                'full_name' => $d->full_name,
                'designation' => $d->designation,
                'image' => $d->signer
                    ? route('signature', $d->signer->id)
                    : null,
                'role' => $d->role,
                'created_at' => $d->created_at ? \Carbon\Carbon::parse($d->created_at)->timezone('Asia/Manila')->format('F d, Y h:i A') : null,
            ];
        }),


        'head_info' => $headInfo,


        'representative_staff' => $representativeUser ? [
            'id' => $representativeUser->id,
            'full_name' => $representativeUser->name,
            'designation' => $representativeUser->designation,
            'image' => $representativeUser->signature
                ? route('signature', $representativeUser->id)
                : null,
            'role' => 'presentative_staff',
            'signed_at' => optional($representativeApproval)->created_at ? \Carbon\Carbon::parse($representativeApproval->created_at)->timezone('Asia/Manila')->format('F d, Y h:i A') : null,
        ] : null,


        'head_authorization_status' => [
            'has_record' => (bool) $headApproval,
            'authorize_file' => $authorizeFile,
        ],
    ]);
}


// public function showupdate($id)
// {
//     $evaluation = Evaluation::with([
//         'office',
//         'criteriaScores',
//         'digitalApprovals',
//         'latestRequest',
//     ])->findOrFail($id);

//     // =========================
//     // PREPARED BY (DIGITAL APPROVAL)
//     // =========================
//     $preparedBy = $evaluation->digitalApprovals()
//         ->where('role', 'Prepared by')
//         ->first();

//     // =========================
//     // SIGNATURE FROM USERS TABLE
//     // =========================
//     $preparedBySignatureUrl = null;

//     if ($preparedBy && $preparedBy->signed_by) {

//         $signer = User::find($preparedBy->signed_by);

//         if ($signer) {
//             $preparedBySignatureUrl = url("/signature/{$signer->id}");
//         }
//     }

//     // =========================
//     // HEAD DIGITAL APPROVAL (for image only)
//     // =========================
//     $headApproval = $evaluation->digitalApprovals()
//         ->with('signer')
//         ->where('role', 'Head')
//         ->latest()
//         ->first();

//     $headImage = ($headApproval && $headApproval->image)
//         ? asset($headApproval->image)
//         : asset('default-avatar.png');

//     // =========================
//     // AUTH USER
//     // =========================
//     /** @var \App\Models\User $user */
//     $user = auth()->user();

//     $offices = ($user->isAdmin() || $user->isPgso())
//         ? Office::orderBy('name')->get()
//         : Office::where('id', $user->office_id)->get();

//     // =========================
//     // OFFICE DATA (SOURCE OF TRUTH)
//     // =========================
//     $office = $evaluation->office;

//     // Head user ONLY for signature lookup
//     $headUser = null;
//     $headSignatureUrl = null;

//     if ($office) {

//         $headUser = User::where('office_id', $office->id)
//             ->where('role', 'head')
//             ->first();

//         if ($headUser && $headUser->signature) {
//             $headSignatureUrl = url("/signature/{$headUser->id}");
//         }
//     }

//     // =========================
//     // HEAD AUTHORIZATION
//     // =========================
//     $headAuthorization = null;

//     if ($headApproval) {

//         $headAuthorization = [

//             'office_id'      => $office?->id,
//             'office_name'    => $office?->name,

//             'user_id'        => $headApproval->signed_by,

//             'head_name'      => $headApproval->full_name,
//             'designation'    => $headApproval->designation,

//             'image'          => $headApproval->image_url,
//             'signature_url'  => $headApproval->signature_url,

//             'linked'         => true,
//         ];

//     } elseif ($office) {

//         $headAuthorization = [

//             'office_id'      => $office->id,
//             'office_name'    => $office->name,

//             'user_id'        => $headUser?->id,

//             'head_name'      => $office->head ?? 'N/A',
//             'designation'    => $office->designation ?? 'N/A',

//             'image'          => null,
//             'signature_url'  => $headSignatureUrl,

//             'linked'         => false,
//         ];
//     }

//     // =========================
//     // AUTHORIZATION FILE
//     // =========================
//     $authorizeFile = null;

//     if ($headApproval && $headApproval->authorize_id) {

//         $authorize = Authorize::with('pdf')
//             ->find($headApproval->authorize_id);

//         if ($authorize && $authorize->pdf) {
//             $authorizeFile = route('secure.pdf', [
//                 'filename' => basename($authorize->pdf->pdf_file)
//             ]);
//         }
//     }

//     // =========================
//     // HEAD AUTHORIZATION STATUS
//     // =========================
//     $headAuthorizationStatus = [
//         'has_record' => (bool) $headApproval,

//         'has_authorize_file' =>
//             ($headApproval && $headApproval->authorize_id) ? true : false,

//         'authorize_id' => optional($headApproval)->authorize_id,

//         'authorize_file' => $authorizeFile,
//     ];

//     // =========================
//     // APPROVED PDF
//     // =========================
//     $pdf = PdfModel::where('user_id', $user->id)
//         ->where('status', 'approved')
//         ->latest()
//         ->first();

//     $pdfStatus = [
//         'has_pdf' => (bool) $pdf,

//         'pdf_id' => $pdf?->id,

//         'pdf_file' => $pdf
//             ? route('secure.pdf', [
//                 'filename' => basename($pdf->pdf_file)
//             ])
//             : null,

//         'has_approved_pdf' => (bool) $pdf,
//     ];

//     // =========================
//     // RESPONSE
//     // =========================
//     return response()->json([

//         'evaluation' => [
//             'id' => $evaluation->id,
//             'supplier_name' => $evaluation->supplier_name,
//             'po_no' => $evaluation->po_no,
//             'date_evaluation' => $evaluation->date_evaluation,
//             'covered_period' => $evaluation->covered_period,
//             'office_id' => $evaluation->office_id,
//             'criteria_scores' => $evaluation->criteriaScores,
//             'latest_request_status' => optional($evaluation->latestRequest)->status,
//         ],

//         'offices' => $offices,
//         'pdf_status' => $pdfStatus,

//         // ✅ FIXED: panel only shows if office has real head
//         'show_head_panel' => (bool) $office?->head,

//         // =========================
//         // PREPARED BY
//         // =========================
//         'prepared_by' => $preparedBy ? [
//             'full_name' => $preparedBy->full_name,
//             'designation' => $preparedBy->designation,
//             'image' => $preparedBy->image,
//             'role' => $preparedBy->role,
//             'signed_by' => $preparedBy->signed_by,
//         ] : null,

//         'prepared_by_signature_url' => $preparedBySignatureUrl,

//         // =========================
//         // HEAD AUTHORIZATION
//         // =========================
//         'head_authorization' => $headAuthorization,
//         'head_authorization_status' => $headAuthorizationStatus,
//     ]);
// }


public function showupdate($id)
{
    $evaluation = Evaluation::with([
        'office',
        'criteriaScores',
        'digitalApprovals',
        'latestRequest',
    ])->findOrFail($id);

    ActivityLog::create([
        'user_id' => auth()->id(),
        'role' => auth()->user()->role,
        'activity' => 'Open Update Form',
        'description' => "Opened update form for PO No. {$evaluation->po_no}.",
        'status' => 'success',
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent(),
    ]);

    /** @var \App\Models\User $user */
    $user = auth()->user();

    $offices = ($user->isAdmin() || $user->isPgso())
        ? Office::orderBy('name')->get()
        : Office::where('id', $user->office_id)->get();

    $office = $evaluation->office;


    $headUser = null;
    $headSignatureUrl = null;

    if ($office) {
        $headUser = User::where('office_id', $office->id)
            ->where('role', 'head')
            ->first();

        if ($headUser) {
            $headSignatureUrl = url("/signature/{$headUser->id}");
        }
    }

    // If logged in user is head or representative staff for this office, use them as fallback/active head authority
    $isRepOrHead = ($user->role === 'head' || $user->role === 'presentative_staff');
    if (!$headUser && $isRepOrHead) {
        $headUser = $user;
        if ($headUser->signature) {
            $headSignatureUrl = url("/signature/{$headUser->id}");
        }
    }

    $headApproval = $evaluation->digitalApprovals()
        ->whereIn('role', ['Head', 'head', 'presentative_staff', 'representative_staff'])
        ->latest()
        ->first();


    $headAuthorization = null;

    if ($office && ($headUser || $headApproval || $isRepOrHead)) {

        $headAuthorization = [
            'office_id'   => $office->id,
            'office_name' => $office->name,

            'user_id'     => $headApproval?->signed_by
                ?? $headUser?->id
                ?? $user->id,

            'head_name'   => $headApproval?->full_name
                ?? $headUser?->name
                ?? $office->head
                ?? $user->name,

            'designation' => $headApproval?->designation
                ?? $headUser?->designation
                ?? $office->designation
                ?? ($user->role === 'presentative_staff' ? 'Representative Staff' : 'Head'),

            'image' => $headApproval?->image,

            'signature_url' => $headSignatureUrl ?? ($headApproval?->signed_by ? url("/signature/{$headApproval->signed_by}") : null),

            'linked' => (bool) $headApproval,
        ];
    }


    $preparedBy = $evaluation->digitalApprovals()
        ->where('role', 'Prepared by')
        ->first();

    $preparedBySignatureUrl = null;

    if ($preparedBy && $preparedBy->signed_by) {
        $preparedBySignatureUrl = url("/signature/{$preparedBy->signed_by}");
    }


    $headAuthorizationStatus = [
        'has_record' => (bool) $headApproval,
        'has_authorize_file' => (bool) ($headApproval?->authorize_id),
        'authorize_id' => $headApproval?->authorize_id ?? null,
    ];


    $pdf = PdfModel::where('user_id', $user->id)
        ->where('status', 'approved')
        ->latest()
        ->first();

    $pdfStatus = [
        'has_pdf' => (bool) $pdf,
        'pdf_id' => $pdf?->id,
        'pdf_file' => $pdf
            ? route('secure.pdf', ['filename' => basename($pdf->pdf_file)])
            : null,
    ];


    return response()->json([
        'evaluation' => [
            'id' => $evaluation->id,
            'supplier_name' => $evaluation->supplier_name,
            'po_no' => $evaluation->po_no,
            'date_evaluation' => $evaluation->date_evaluation,
            'covered_period' => $evaluation->covered_period,
            'office_id' => $evaluation->office_id,
            'criteria_scores' => $evaluation->criteriaScores,
            'latest_request_status' => optional($evaluation->latestRequest)->status,
        ],

        'offices' => $offices,
        'pdf_status' => $pdfStatus,


        'show_head_panel' => (bool) $headUser,


        'head_authorization' => $headAuthorization,

        'head_authorization_status' => $headAuthorizationStatus,


        'prepared_by' => $preparedBy ? [
            'full_name' => $preparedBy->full_name,
            'designation' => $preparedBy->designation,
            'image' => $preparedBy->image,
            'role' => $preparedBy->role,
            'signed_by' => $preparedBy->signed_by,
        ] : null,

        'prepared_by_signature_url' => $preparedBySignatureUrl,
    ]);
}


// public function update(Request $request, $id)
// {
//     DB::beginTransaction();

//     try {
//         $evaluation = Evaluation::findOrFail($id);
//         $user = auth()->user();

//         // =====================================================
//         // SECURITY CHECK
//         // =====================================================
//         /** @var User $user */
//         if (!$user->isAdmin() && $evaluation->office_id !== $user->office_id) {
//             abort(403, 'Unauthorized action.');
//         }

//         // =====================================================
//         // GET LATEST REQUEST
//         // =====================================================
//         $requestRecord = $evaluation->latestRequest;

//         // =====================================================
//         // STATUS LOGIC
//         // =====================================================
//         if ($requestRecord?->status === 'approved') {

//             // Keep evaluation submitted after approval
//             $newStatus = 'submitted';

//         } else {

//             if ($user->isHead()) {
//                 $newStatus = 'submitted';
//             } elseif ($user->isEndUser()) {
//                 $newStatus = 'head review';
//             } else {
//                 // admin / pgso / others
//                 $newStatus = $evaluation->status;
//             }
//         }

//         // =====================================================
//         // UPDATE CORE EVALUATION
//         // =====================================================
//         $evaluation->update([
//             'supplier_name'   => $request->supplier_name ?? $evaluation->supplier_name,
//             'po_no'           => $request->po_no ?? $evaluation->po_no,
//             'date_evaluation' => !empty($request->date_evaluation)
//                 ? Carbon::parse($request->date_evaluation)->timezone('Asia/Manila')
//                 : $evaluation->date_evaluation,
//             'covered_period'  => $request->covered_period ?? $evaluation->covered_period,
//             'period_year'     => $request->period_year ?? $evaluation->period_year,
//             'office_id'       => $request->office_id ?? $evaluation->office_id,
//             'status'          => $newStatus,
//         ]);

//         // =====================================================
//         // REQUEST STATUS UPDATE
//         // =====================================================
//         if ($requestRecord?->status === 'approved') {
//             $requestRecord->update([
//                 'status'      => 'done',
//                 'status_date' => now(),
//             ]);
//         }

//         // =====================================================
//         // CRITERIA SCORES
//         // =====================================================
//         foreach ($request->criteria_scores ?? [] as $score) {
//             CriteriaScore::updateOrCreate(
//                 [
//                     'evaluation_id' => $evaluation->id,
//                     'criteria_id'   => $score['criteria_id'],
//                 ],
//                 [
//                     'number_rating' => $score['number_rating'] ?? 0,
//                     'remarks'       => $score['remarks'] ?? null,
//                 ]
//             );
//         }

//         // =====================================================
//         // DIGITAL APPROVAL - END USER / PREPARED BY
//         // =====================================================
//         if ($user->isEndUser() && !empty($request->evaluator)) {

//             $preparedBy = DigitalApproval::updateOrCreate(
//                 [
//                     'evaluation_id' => $evaluation->id,
//                     'role'          => 'Prepared by',
//                 ],
//                 [
//                     'full_name'   => $request->evaluator['full_name'] ?? null,
//                     'designation' => $request->evaluator['designation'] ?? null,
//                     'signed_by'   => $request->evaluator['user_id'] ?? $user->id,
//                 ]
//             );

//             $image = $request->evaluator['image'] ?? null;

//             if (!empty($image) && str_contains($image, 'data:image')) {

//                 $imageData = base64_decode(
//                     preg_replace('#^data:image/\w+;base64,#i', '', $image)
//                 );

//                 if ($imageData !== false) {

//                     if (!empty($preparedBy->image)) {
//                         $oldPath = str_replace('/storage/', '', $preparedBy->image);

//                         if (Storage::disk('public')->exists($oldPath)) {
//                             Storage::disk('public')->delete($oldPath);
//                         }
//                     }

//                     $fileName = 'photos/photo_signature/' . Str::uuid() . '.png';

//                     Storage::disk('public')->put($fileName, $imageData);

//                     $preparedBy->update([
//                         'image' => '/storage/' . $fileName,
//                     ]);
//                 }
//             }
//         }

//         // =====================================================
//         // DIGITAL APPROVAL - HEAD
//         // =====================================================
//         if ($user->isHead()) {

//             $office = $evaluation->office;

//             $headUser = User::where('office_id', $office->id)
//                 ->where('role', 'head')
//                 ->first();

//             if (!$headUser) {
//                 throw new \Exception('Head user not found for this office.');
//             }

//             DigitalApproval::updateOrCreate(
//                 [
//                     'evaluation_id' => $evaluation->id,
//                     'role'          => 'Head',
//                 ],
//                 [
//                     'full_name'   => $headUser->name,
//                     'designation' => $office->designation ?? 'N/A',
//                     'signed_by'   => $headUser->id,
//                     'image'       => $headUser->signature,
//                 ]
//             );
//         }

//         // =====================================================
//         // REVIEW LINK
//         // =====================================================
//         $link = EvaluationLink::firstOrCreate(
//             [
//                 'evaluation_id' => $evaluation->id,
//             ],
//             [
//                 'token'        => Str::uuid()->toString(),
//                 'code'         => strtoupper(Str::random(6)),
//                 'expires_at'   => null,
//                 'is_completed' => false,
//             ]
//         );

//         DB::commit();

//         return response()->json([
//             'success'      => true,
//             'message'      => 'Evaluation updated successfully!',
//             'status'       => $newStatus,
//             'review_link'  => url('/evaluation/head-review/' . $link->token),
//             'review_token' => $link->token,
//             'review_code'  => $link->code,
//         ]);

//     } catch (\Exception $e) {

//         DB::rollBack();

//         Log::error('Error updating evaluation', [
//             'error' => $e->getMessage(),
//         ]);

//         return response()->json([
//             'success' => false,
//             'message' => config('app.debug')
//                 ? $e->getMessage()
//                 : 'An unexpected error occurred.',
//         ], 500);
//     }
// }



public function update(Request $request, $id)
{
    DB::beginTransaction();

    try {
        $evaluation = Evaluation::findOrFail($id);
        $user = auth()->user();


        /** @var User $user */
        if (!$user->isAdmin() && !$user->isPgso()) {

            // Head and presentative_staff can directly evaluate 'head review' evaluations
            $canDirectEvaluate = ($user->isHead() || $user->role === 'presentative_staff')
                && $evaluation->status === 'head review';

            if (!$canDirectEvaluate) {
                $hasApprovedRequest = Requests::where('evaluation_id', $evaluation->id)
                    ->where('user_id', $user->id)
                    ->where('status', 'approved')
                    ->exists();

                if (!$hasApprovedRequest && in_array($evaluation->status, ['submitted', 'head review', 'approved', 'done'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'An approved update request is required to edit this evaluation.'
                    ], 403);
                }
            }
        }

        // Determine new status based on user role
        $hasApprovedUpdateRequest = Requests::where('evaluation_id', $evaluation->id)
            ->where('user_id', auth()->id())
            ->where('status', 'approved')
            ->where('request_type', 'update')
            ->exists();

        if ($user->isEndUser()) {
            if ($hasApprovedUpdateRequest) {
                // End user updating via approved request → keep status unchanged
                $newStatus = $evaluation->status;
            } else {
                // End user doing a fresh evaluation from pending → moves to head review
                $newStatus = 'head review';
            }
        } elseif ($user->isHead() || $user->role === 'presentative_staff') {
            // Head or presentative staff evaluates a head review evaluation → moves to submitted
            $newStatus = 'submitted';
        } else {
            // Admin / PGSO / others → preserve existing status
            $newStatus = $evaluation->status;
        }

        $evaluation->update([
            'supplier_name'   => $request->supplier_name ?? $evaluation->supplier_name,
            'po_no'           => $request->po_no ?? $evaluation->po_no,
            'date_evaluation' => !empty($request->date_evaluation)
                ? Carbon::parse($request->date_evaluation)->timezone('Asia/Manila')
                : $evaluation->date_evaluation,
            'covered_period'  => $request->covered_period ?? $evaluation->covered_period,
            'period_year'     => $request->period_year ?? $evaluation->period_year,
            'office_id'       => $request->office_id ?? $evaluation->office_id,
            'status'          => $newStatus,
        ]);

        // Update all approved requests for this evaluation to done
        Requests::where('evaluation_id', $evaluation->id)
            ->where('status', 'approved')
            ->update([
                'status'      => 'done',
                'status_date' => now(),
            ]);

        foreach ($request->criteria_scores ?? [] as $score) {
            CriteriaScore::updateOrCreate(
                [
                    'evaluation_id' => $evaluation->id,
                    'criteria_id'   => $score['criteria_id'],
                ],
                [
                    'number_rating' => $score['number_rating'] ?? 0,
                    'remarks'       => $score['remarks'] ?? null,
                ]
            );
        }

        if ($user->isEndUser() && !empty($request->evaluator)) {
            $preparedBy = DigitalApproval::updateOrCreate(
                [
                    'evaluation_id' => $evaluation->id,
                    'role'          => 'Prepared by',
                ],
                [
                    'full_name'   => $request->evaluator['full_name'] ?? null,
                    'designation' => $request->evaluator['designation'] ?? null,
                    'signed_by'   => $request->evaluator['user_id'] ?? $user->id,
                ]
            );

            $image = $request->evaluator['image'] ?? null;

            if (!empty($image) && str_contains($image, 'data:image')) {
                $imageData = base64_decode(
                    preg_replace('#^data:image/\w+;base64,#i', '', $image)
                );

                if ($imageData !== false) {
                    if (!empty($preparedBy->image)) {
                        $oldPath = str_replace('/storage/', '', $preparedBy->image);
                        if (Storage::disk('public')->exists($oldPath)) {
                            Storage::disk('public')->delete($oldPath);
                        }
                    }

                    $fileName = 'photos/photo_signature/' . Str::uuid() . '.png';
                    Storage::disk('public')->put($fileName, $imageData);
                    $preparedBy->update([
                        'image' => '/storage/' . $fileName,
                    ]);
                }
            }
        }

        if ($user->isHead() || $user->role === 'presentative_staff') {
            $office = $evaluation->office;
            $headUser = $office
                ? User::where('office_id', $office->id)->where('role', 'head')->first()
                : null;

            if (!$headUser) {
                $headUser = $user;
            }


            $actingUser = $user;


            $approvalRole = $user->role === 'presentative_staff'
                ? 'representative_staff'
                : 'Head';

            DigitalApproval::updateOrCreate(
                [
                    'evaluation_id' => $evaluation->id,
                    'role'          => $approvalRole,
                ],
                [
                    'full_name'   => $actingUser->name,

                    'designation' => $user->role === 'presentative_staff'
                        ? ($actingUser->designation ?? 'Representative Staff')
                        : ($headUser->designation ?? 'Head'),

                    'signed_by'   => $actingUser->id,

                    'image'       => $actingUser->signature,
                ]
            );
        }

        DB::commit();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'role' => auth()->user()->role,
            'activity' => 'Update Evaluation',
            'description' => "Updated supplier evaluation for PO No. {$evaluation->po_no}.",
            'status' => 'success',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Evaluation updated successfully!',
            'status'  => $newStatus,
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        Log::error('Error updating evaluation', [
            'error' => $e->getMessage(),
        ]);

        return response()->json([
            'success' => false,
            'message' => config('app.debug')
                ? $e->getMessage()
                : 'An unexpected error occurred.',
        ], 500);
    }
}

public function evaluationsList(Request $request)
{
    $user = $request->user();
    $status = strtolower($request->query('status', 'pending'));

    $query = Evaluation::with([
        'digitalApprovals',
        'criteriaScores',
        'requests',
        'purchaseOrder',
        'office'
    ])
    ->where('status', $status)
    ->where(function ($q) {
        $q->where('delete_status', 0)
          ->orWhereNull('delete_status');
    });


    if (!$user->isAdmin() && $user->role !== 'pgso') {
        $query->where('office_id', $user->office_id);
    }


    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('po_no', 'like', "%{$search}%")
              ->orWhere('supplier_name', 'like', "%{$search}%")
              ->orWhereHas('office', function ($qo) use ($search) {
                  $qo->where('name', 'like', "%{$search}%");
              })
              ->orWhereHas('digitalApprovals', function ($qa) use ($search) {
                  $qa->where('role', 'Prepared By')
                     ->where('full_name', 'like', "%{$search}%");
              });
        });
    }


    if ($request->filled('period_year')) {
    $query->where('period_year', $request->period_year);
    }

    $evaluations = $query->latest()->get()->map(function ($evaluation) {

        // GET LATEST REQUEST
        $latestRequest = $evaluation->requests
            ->sortByDesc('created_at')
            ->first();


        $percentageMap = [
            1 => [4 => 20, 3 => 15, 2 => 10, 1 => 5],
            2 => [4 => 30, 3 => 22.5, 2 => 15, 1 => 7.5],
            3 => [4 => 25, 3 => 18.75, 2 => 12.5, 1 => 6.25],
            4 => [4 => 25, 3 => 18.75, 2 => 12.5, 1 => 6.25],
        ];

        $totalScore = 0;

        foreach ($evaluation->criteriaScores as $score) {
            $criteriaId = $score->criteria_id;
            $rating = $score->number_rating;

            if (isset($percentageMap[$criteriaId][$rating])) {
                $totalScore += $percentageMap[$criteriaId][$rating];
            }
        }

        $preparedBy = $evaluation->digitalApprovals
            ->firstWhere('role', 'Prepared By');

        $poPdfUrl = null;
        if ($evaluation->purchaseOrder && $evaluation->purchaseOrder->pdf_po) {
            $poPdfUrl = route('po.view.pdf', $evaluation->purchaseOrder->encrypted_id);
        }

        return [
            'id' => $evaluation->id,
            'po_no' => $evaluation->po_no,
            'po_pdf_url' => $poPdfUrl,
            'supplier_name' => $evaluation->supplier_name,

            'office_name' => optional($evaluation->office)->name,
            'evaluator' => optional($preparedBy)->full_name,
            'end_user' => optional($evaluation->purchaseOrder)->end_user,


            'average_score' => $totalScore ? round($totalScore, 2) : '-',


            'date_evaluation' => $evaluation->date_evaluation
                ? \Carbon\Carbon::parse($evaluation->date_evaluation)->format('M d, Y')
                : '-',


            'period_year' => $evaluation->period_year,

            'status' => $evaluation->status,


            'request_status' => optional($latestRequest)->status,
        ];
    });

    return response()->json($evaluations);
}

public function fetchRequestsForTable(Request $request)
{
    $user = $request->user();

    $query = Requests::with(['evaluation.office']);


    $query->where(function ($q) {
        $q->whereHas('evaluation', function ($sub) {
            $sub->where('status', '!=', 'deleted');
        })
        // OR allow delete requests even if evaluation is deleted
        ->orWhere('request_type', 'delete');
    });


    $query->where(function ($q) use ($user) {

        if (in_array($user->role, ['administrator', 'pgso'])) {
            $q->where('requested_to', $user->id);
        } else {
            $q->where('user_id', $user->id);
        }
    });


    if (!$user->isAdmin() && $user->role !== 'pgso') {
        $query->whereHas('evaluation', function ($q) use ($user) {
            $q->where('office_id', $user->office_id);
        });
    }


    $requests = $query->latest()->get()->map(function ($req) {

        return [
            'id' => $req->id,
            'po_no' => $req->evaluation->po_no ?? '-',
            'supplier_name' => $req->evaluation->supplier_name ?? '-',
            'office_name' => optional($req->evaluation->office)->name ?? '-',
            'request_type' => $req->request_type,
            'reason' => $req->reason ?? '-',
            'status' => $req->status,

            'request_date' => $req->request_date
                ? \Carbon\Carbon::parse($req->request_date)->format('Y-m-d')
                : '-',

            'requested_to' => $req->requested_to,
        ];
    });

    return response()->json($requests);
}

public function fetchRequestsForDropdown(Request $request)
{
    $user = $request->user();

    $query = \App\Models\Evaluation::with('office')
        ->whereIn('status', ['submitted', 'pending', 'head review'])
        ->whereDoesntHave('requests', function ($q) {
            $q->whereIn('status', ['request', 'approved', 'rejected']);
        });


    if (!$user->isAdmin() && $user->role !== 'pgso') {
        $query->where('office_id', $user->office_id);
    }

    $evaluations = $query
        ->select('id', 'po_no')
        ->distinct()
        ->get()
        ->map(function ($e) {
            return [
                'id' => $e->id,
                'po_no' => $e->po_no,
                'office_name' => optional($e->office)->name,
            ];
        });

    return response()->json($evaluations);
}


public function approve(Requests $request)
{
    DB::beginTransaction();

    try {


        $request->status = 'approved';
        $request->status_date = now();
        $request->save();

        $evaluation = Evaluation::findOrFail($request->evaluation_id);


        if ($request->request_type === 'delete') {


            $evaluation->update([
                'status' => 'deleted'
            ]);


            $purchaseOrder = PurchaseOrder::where('po_no', $evaluation->po_no)->first();

            if ($purchaseOrder) {
                $purchaseOrder->update([
                    'status' => null
                ]);
            }
        }


        if ($request->request_type === 'update') {

            // $evaluation->update(['status' => 'pending']);
        }

        DB::commit();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'role' => auth()->user()->role,
            'activity' => 'Approve Request',
            'description' => "Approved {$request->request_type} request for PO No. {$evaluation->po_no}.",
            'status' => 'success',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Request approved successfully!'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

public function reject(Requests $request)
{
    $request->status = 'rejected';
    $request->status_date = now();
    $request->save();

    ActivityLog::create([
        'user_id' => auth()->id(),
        'role' => auth()->user()->role,
        'activity' => 'Reject Request',
        'description' => "Rejected {$request->request_type} request for PO No. {$request->evaluation->po_no}.",
        'status' => 'success',
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent(),
    ]);


    return response()->json(['message' => 'Request rejected successfully!']);
}

public function cancel(Requests $request)
{

    if (in_array($request->status, ['approved', 'rejected', 'done'])) {
        return response()->json([
            'message' => 'This request cannot be cancelled.',
        ], 400);
    }


    $request->status = 'cancelled';
    $request->status_date = now();
    $request->save();

    ActivityLog::create([
        'user_id' => auth()->id(),
        'role' => auth()->user()->role,
        'activity' => 'Cancel Request',
        'description' => "Cancelled {$request->request_type} request for PO No. {$request->evaluation->po_no}.",
        'status' => 'success',
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent(),
    ]);

    return response()->json([
        'message' => 'Request cancelled successfully!',
    ]);
}


public function countPendingEvaluations(Request $request)
{
    $user = $request->user();

    $query = Evaluation::notDeleted()
        ->where('status', 'pending');

    if (!$user->isAdmin() && $user->role !== 'pgso') {
        $query->where('office_id', $user->office_id);
    }

    return response()->json([
        'pending' => $query->count()
    ]);
}

public function countHeadEvaluations(Request $request)
{
    $user = $request->user();

    $query = Evaluation::notDeleted()
        ->where('status', 'head review');

    if (!$user->isAdmin() && $user->role !== 'pgso') {
        $query->where('office_id', $user->office_id);
    }

    return response()->json([
        'head' => $query->count()
    ]);
}

public function countApproveEvaluations(Request $request)
{
    $user = $request->user();

    $query = Evaluation::notDeleted()
        ->where('status', 'submitted');

    if (!$user->isAdmin() && $user->role !== 'pgso') {
        $query->where('office_id', $user->office_id);
    }

    return response()->json([
        'approve' => $query->count()
    ]);
}

public function getReviewLink($id)
{
    $evaluation = Evaluation::with('evaluationLink')->findOrFail($id);

    $link = $evaluation->evaluationLink;

    return response()->json([
        'token' => optional($link)->token,
        'code' => optional($link)->code,
    ]);
}



public function getDepartments(Request $request)
{
    $user = $request->user();

    if ($user->isAdmin() || $user->isPgso()) {

        $departments = Office::query()
            ->where('name', '!=', 'PGSO-Warehouse')
            ->orderBy('name')
            ->get(['id', 'name']);

    } else {

        $departments = Office::where('id', $user->office_id)
            ->get(['id', 'name']);
    }

    return response()->json($departments);
}



public function download($id)
{
    $evaluation = Evaluation::with([
        'office',
        'digitalApprovals.signer'
    ])->findOrFail($id);

    $pdf = Pdf::loadView(
        'pdf.download',
        compact('evaluation')
    );

    ActivityLog::create([
        'user_id' => auth()->id(),
        'role' => auth()->user()->role,
        'activity' => 'Download PDF',
        'description' => "Downloaded evaluation PDF for PO No. {$evaluation->po_no}.",
        'status' => 'success',
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent(),
    ]);


return $pdf->download(
    'supplier-evaluation-' .
    Str::slug($evaluation->supplier_name) . '-' .
    \Carbon\Carbon::parse($evaluation->date_evaluation ?? $evaluation->created_at)
        ->format('Y-m-d_H-i-s') .
    '.pdf'
);
}

public function destroy($id)
{
    $evaluation = Evaluation::findOrFail($id);

    // mark evaluation as deleted
    $evaluation->delete_status = 1;
    $evaluation->updated_at = now();
    $evaluation->save();

    
    $po = PurchaseOrder::where('po_no', $evaluation->po_no)->first();

    if ($po) {
        $po->status = 'Pending';
        $po->save();
    }

    ActivityLog::create([
        'user_id' => auth()->id(),
        'role' => auth()->user()->role,
        'activity' => 'Recycle Evaluation',
        'description' => "Moved evaluation with PO No. {$evaluation->po_no} to the recycle bin.",
        'status' => 'success',
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent(),
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Evaluation moved to Recycle Bin and PO status cleared.'
    ]);
}

public function deletedList()
{
    return Evaluation::where('delete_status', 1)
        ->latest()
        ->get();
}

public function restore($id)
{
    $evaluation = Evaluation::findOrFail($id);
    $evaluation->delete_status = 0;
    $evaluation->updated_at = now();
    $evaluation->save();

    // update related purchase order status to NULL
    $po = PurchaseOrder::where('po_no', $evaluation->po_no)->first();

    if ($po) {
        $po->status = 'Added';
        $po->save();
    }


    ActivityLog::create([
        'user_id' => auth()->id(),
        'role' => auth()->user()->role,
        'activity' => 'Restore Evaluation',
        'description' => "Restored evaluation with PO No. {$evaluation->po_no}.",
        'status' => 'success',
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent(),
    ]);

    return response()->json(['success' => true]);
}

public function forceDelete($id)
{
    $evaluation = Evaluation::findOrFail($id);
    $evaluation->delete(); // permanent

    $poNo = $evaluation->po_no;

    $evaluation->delete();

    ActivityLog::create([
        'user_id' => auth()->id(),
        'role' => auth()->user()->role,
        'activity' => 'Permanent Delete',
        'description' => "Permanently deleted evaluation with PO No. {$poNo}.",
        'status' => 'success',
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent(),
    ]);


    return response()->json(['success' => true]);
}

public function countRequests(Request $request)
{
    $user = $request->user();

    $query = \App\Models\Requests::query()

        // only ACTIVE requests
        ->where('status', 'request')

        // exclude deleted evaluations
        ->whereHas('evaluation', function ($q) {
            $q->where('status', '!=', 'deleted');
        });

    // END USER: sees all their requests (still only "request" status)
    if ($user->role === 'end_user') {

        $count = $query
            ->where('user_id', $user->id)
            ->count();
    }

    // ADMIN / PGSO: only assigned requests
    elseif (in_array($user->role, ['administrator', 'pgso'])) {

        $count = (clone $query)
            ->where('requested_to', $user->id)
            ->count();
    }

    else {
        $count = 0;
    }

    return response()->json([
        'count' => $count
    ]);
}

}
