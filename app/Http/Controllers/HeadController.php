<?php

namespace App\Http\Controllers;

use App\Models\CriteriaScore;
use App\Models\DigitalApproval;
use App\Models\EvaluationLink;
use App\Models\Pdf;
use App\Models\PurchaseOrder;
use App\Models\Requests;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class HeadController extends Controller
{
    /**
     * Show the evaluation review page for Head using a token.
     *
     * @param string $token
     * @return \Illuminate\View\View
     */
public function reviewPage($token)
{
    try {

        $link = EvaluationLink::with([
                'evaluation.criteriaScores',
                'evaluation.digitalApprovals'
            ])
            ->where('token', $token)
            ->where('is_used', 0)
            ->where(function ($query) {

                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->first();

        if (!$link || !$link->evaluation) {
            // Handle missing link
            Log::warning('Invalid or expired evaluation link', ['token' => $token]);
            abort(404, 'This review link is invalid or has expired.');
        }

        $evaluation = $link->evaluation;

        return view('head.review', [
            'evaluation' => $evaluation,
            'token'      => $token
        ]);

    } catch (\Exception $e) {
        Log::error('Error loading evaluation review page', [
            'token' => $token,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        abort(500, 'An unexpected error occurred while opening this review page.');
    }
}

public function reviewEvaluation($token)
{
    $link = EvaluationLink::with([
        'evaluation.office',
        'evaluation.criteriaScores.criteria',
        'evaluation.digitalApprovals',
        'evaluation.latestRequest',
    ])
    ->active()
    ->where('token', $token)
    ->first();

    if (!$link) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid or expired evaluation link.'
        ], 404);
    }

    $evaluation = $link->evaluation;
    $request = $evaluation->latestRequest;


    if ($evaluation->status === 'head review') {

    } elseif (
        !$request ||
        $request->status !== 'approved' ||
        $evaluation->status !== 'submitted'
    ) {
        return response()->json([
            'success' => false,
            'message' => 'This evaluation is not yet approved for review.'
        ], 403);
    }


    $criteriaData = [];

    foreach ($evaluation->criteriaScores as $score) {

        if (!$score->criteria) continue;

        $name = strtoupper(trim($score->criteria->criteria_name));

        switch ($name) {
            case 'PRICE':
                $key = 'price_1';
                break;

            case 'QUALITY/SERVICE LEVEL':
                $key = 'quality_1';
                break;

            case 'CUSTOMER CARE/AFTER SALES SERVICE':
                $key = 'customercare_1';
                break;

            case 'DELIVERY FULFILLMENT':
                $key = 'delivery_1';
                break;

            default:
                continue 2;
        }

        $criteriaData[$key] = [
            'value'   => $score->number_rating,
            'remarks' => $score->remarks
        ];
    }


    $approvals = $evaluation->digitalApprovals->map(function ($a) {

        $imageUrl = null;

        if (!empty($a->image)) {
            $imageUrl = str_starts_with($a->image, 'http')
                ? $a->image
                : asset($a->image);
        }

        return [
            'role'        => strtolower(trim($a->role)),
            'full_name'   => $a->full_name,
            'designation' => $a->designation,
            'image'       => $imageUrl,
        ];
    });


return response()->json([
    'success'           => true,
    'evaluation_id'     => $evaluation->id,
    'supplier_name'     => $evaluation->supplier_name,
    'po_no'             => $evaluation->po_no,
    'date_evaluation'   => $evaluation->date_evaluation,
    'covered_period'    => $evaluation->covered_period,

    // ✅ IMPORTANT: include full office object
    'office' => $evaluation->office ? [
        'id'          => $evaluation->office->id,
        'name'        => $evaluation->office->name,
        'head'        => $evaluation->office->head,
        'designation' => $evaluation->office->designation,
    ] : null,

    'criteria'          => $criteriaData,
    'digital_approvals' => $evaluation->digitalApprovals
]);
}


public function updateEvaluation(Request $request, $token)
{
    DB::beginTransaction();

    try {


        $link = EvaluationLink::with('evaluation.criteriaScores', 'evaluation.digitalApprovals')
                ->active()
                ->where('token', $token)
                ->firstOrFail();

        $evaluation = $link->evaluation;



        $evaluation->update([
            'supplier_name'   => $request->supplier_name ?? $evaluation->supplier_name,
            'po_no'           => $request->po_no ?? $evaluation->po_no,
            'date_evaluation' => !empty($request->date_evaluation)
                                    ? Carbon::parse($request->date_evaluation)->timezone('Asia/Manila')
                                    : $evaluation->date_evaluation,
            'covered_period'  => $request->covered_period ?? $evaluation->covered_period,
            'office_id' => $request->office_id ?? $evaluation->office_id,
            'status'          => 'submitted',
        ]);



        $criteriaScores = $request->criteria ?? [];

        $criteriaIdMap = [
            'price_1'        => 1,
            'quality_1'      => 2,
            'customercare_1' => 3,
            'delivery_1'     => 4,
        ];

        foreach ($criteriaScores as $key => $data) {

            if (!isset($criteriaIdMap[$key])) {
                continue;
            }

            $criteriaId = $criteriaIdMap[$key];

            CriteriaScore::updateOrCreate(
                [
                    'evaluation_id' => $evaluation->id,
                    'criteria_id'   => $criteriaId
                ],
                [
                    'number_rating' => $data['value'] ?? null,
                    'remarks'       => $data['remarks'] ?? null
                ]
            );
        }


        $requestRecord = $evaluation->requests()
            ->where('status', 'approved')
            ->latest('id')
            ->first();

        if ($requestRecord) {
            $requestRecord->update([
                'status'      => 'done',
                'status_date' => now()
            ]);
        }



        if (!empty($request->evaluator) && !empty($request->role)) {

            $role = $request->role;

            $digitalApproval = DigitalApproval::firstOrNew([
                'evaluation_id' => $evaluation->id,
                'role'          => $role
            ]);


    $validator = Validator::make($request->all(), [
        'evaluator.full_name'   => $digitalApproval->full_name ? 'nullable|string' : 'required|string',
        'evaluator.designation' => $digitalApproval->designation ? 'nullable|string' : 'required|string',
        'evaluator.image'       => $digitalApproval->image ? 'nullable|string' : 'required|string',
        'role'                  => 'required|string',
    ], [
        'evaluator.full_name.required'   => 'Full name of the evaluator is required.',
        'evaluator.designation.required' => 'Designation of the evaluator is required.',
        'role.required'                  => 'Role is required.',
        'evaluator.image.required'       => 'Digital signature image is required.',
    ]);
                    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors'  => $validator->errors()
        ], 422);
    }

            $digitalApproval->full_name   = $request->evaluator['full_name'] ?? $digitalApproval->full_name;
            $digitalApproval->designation = $request->evaluator['designation'] ?? $digitalApproval->designation;



            if (!empty($request->evaluator['image']) && str_contains($request->evaluator['image'], 'data:image')) {

                $image = $request->evaluator['image'];

                $image = preg_replace('#^data:image/\w+;base64,#i', '', $image);
                $imageData = base64_decode($image);

                if ($imageData !== false) {


                    if (!empty($digitalApproval->image)) {

                        $oldPath = str_replace('/storage/', '', $digitalApproval->image);

                        if (Storage::disk('public')->exists($oldPath)) {
                            Storage::disk('public')->delete($oldPath);
                        }
                    }


                    $fileName = 'photos/' . Str::uuid() . '.png';

                    Storage::disk('public')->put($fileName, $imageData);

                    $digitalApproval->image = '/storage/' . $fileName;
                }
            }

            $digitalApproval->save();
        }


        $link->update([
            'token' => Str::random(64),
            'code'  => strtoupper(Str::random(10)),
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Evaluation submitted successfully!',
            'warning' => 'Security update: The evaluation link has been regenerated. The old link is no longer valid.',
            'new_token' => $link->token,
            'new_code' => $link->code,
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        Log::error('Error updating evaluation', [
            'error_message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => env('APP_DEBUG') ? $e->getMessage() : 'An unexpected error occurred.'
        ], 500);
    }
}





public function validateCode(Request $request, $token)
{
    $validator = Validator::make($request->all(), [
        'code' => 'required|string'
    ]);

    if ($validator->fails()) {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors'  => $validator->errors()
        ], 422));
    }

    $link = EvaluationLink::active()
        ->where('token', $token)
        ->first();

    if (!$link) {
        return response()->json([
            'success' => false,
            'message' => 'This link is invalid or expired.'
        ], 404);
    }

    if ($link->code !== $request->code) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid review code.'
        ], 422);
    }

    return response()->json([
        'success' => true
    ]);
}


public function dashboard()
{
    $users = User::whereIn('role', ['administrator', 'pgso'])
        ->where('status', 'active')
        ->orderBy('name')
        ->get();

    $pos = PurchaseOrder::orderByRaw('CASE WHEN pdf_po IS NULL OR pdf_po = "" THEN 0 ELSE 1 END')
        ->latest()
        ->get();


    $requestMessages = Requests::with('evaluation')
        ->where('user_id', auth()->id())
        ->where('status', '!=', 'request') // keep but safe now
        ->latest()
        ->get()
        ->map(function ($request) {
            return (object)[
                'type'          => 'request',
                'evaluation_id' => $request->evaluation_id ?? ($request->evaluation->id ?? null),
                'po_no'         => $request->evaluation->po_no ?? 'No PO Number',
                'status'        => $request->status,
                'created_at'    => $request->created_at,
            ];
        });



    $pdfMessages = Pdf::where('user_id', auth()->id())
        ->where('status', 'approved')
        ->latest()
        ->get()
        ->map(function ($pdf) {

            return (object)[
                'type' => 'pdf',
                'po_no' => 'PDF Document',
                'status' => 'approved',
                'created_at' => $pdf->created_at,
            ];
        });


    $messages = collect($requestMessages)
        ->merge($pdfMessages)
        ->sortByDesc(function ($item) {
            return $item->created_at;
        })
        ->values()
        ->take(10);

    $messageCount = $messages->count();

    return view('head.dashboard', compact(
        'users',
        'pos',
        'messages',
        'messageCount'
    ));
}
}
