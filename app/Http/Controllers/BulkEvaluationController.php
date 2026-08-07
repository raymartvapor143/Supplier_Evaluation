<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CriteriaScore;
use App\Models\DigitalApproval;
use App\Models\Evaluation;
use App\Models\Office;
use App\Models\PurchaseOrder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class BulkEvaluationController extends Controller
{


public function bulkSupplierPage()
{
    /** @var User $user */
    $user = auth()->user();

    $query = Evaluation::query();

    if ($user->isEndUser()) {

        $query->where('status', 'pending')
              ->where('office_id', $user->office_id);

    } elseif ($user->isHead()) {

        $query->where('status', 'head review')
              ->where('office_id', $user->office_id);

    } elseif ($user->isPgso()) {

        $query->where('status', 'pgso review');
    } elseif ($user->isPresentativeStaff()) {

        $query->where('status', 'head review')
              ->where('office_id', $user->office_id);

    }


    $headUser = User::where('role', 'head')
        ->where('office_id', $user->office_id)
        ->first();


    $office = $user->office;

    $headName = $headUser?->name ?? $office?->head;
    $headDesignation = $headUser?->designation ?? $office?->designation;

    $evaluations = $query
        ->orderBy('date_evaluation', 'desc')
        ->get();

    return view('layouts.bulkpage', [
        'evaluations' => $evaluations,
        'headUser' => $headUser,
        'headName' => $headName,
        'headDesignation' => $headDesignation,
        'endUser' => $user,
        'presentativeUser' => $user,
        'authLetterSubmitted' => $user->authorization_letter ?? false,
        'isEndUser' => $user->isEndUser(),
        'isHead' => $user->isHead(),
        'isPgso' => $user->isPgso(),
    ]);
}

public function getSuppliers()
{
    $user = auth()->user();

    $query = Evaluation::query()
        ->selectRaw('supplier_name, COUNT(*) as total')
        ->whereNotNull('supplier_name')
        ->where('supplier_name', '!=', '');

    // END USER
     /** @var User $user */
    if ($user->isEndUser()) {

        $query->where('status', 'pending')
              ->where('office_id', $user->office_id);

    }

    // HEAD
    elseif ($user->isHead()) {

        $query->where('status', 'head review')
              ->where('office_id', $user->office_id);

    }

    // PGSO
    elseif ($user->isPgso()) {

        $query->where('status', 'pgso review');

    }

    $suppliers = $query
        ->groupBy('supplier_name')
        ->orderBy('supplier_name')
        ->get();

    return response()->json($suppliers);
}


public function showBulkEvaluationData(Evaluation $evaluation)
{
    $evaluation->load([
        'office',
        'criteriaScores',
        'digitalApprovals.signer'
    ]);

    return response()->json([
        'success' => true,
        'evaluation' => $evaluation
    ]);
}


public function fetchEvaluations(Request $request)
{
    $request->validate([
        'supplier_name' => 'required|string',
    ]);

    $user = auth()->user();

    $query = Evaluation::where(
        'supplier_name',
        $request->supplier_name
    );

    // END USER
     /** @var User $user */
    if ($user->isEndUser()) {

        $query->where('status', 'pending')
              ->where('office_id', $user->office_id);

    }

    // HEAD
    elseif ($user->isHead()) {

        $query->where('status', 'head review')
              ->where('office_id', $user->office_id);

    }

    // PGSO
    elseif ($user->isPgso()) {

        $query->where('status', 'pgso review');

    }

    // ADMIN
    elseif ($user->isAdmin()) {


    }

    $evaluations = $query
        ->orderBy('date_evaluation', 'desc')
        ->get();

    return response()->json($evaluations);
}

    // Save selected evaluations
    public function saveSelected(Request $request)
    {
        $request->validate([
            'evaluation_ids' => 'required|array',
            'evaluation_ids.*' => 'integer|exists:evaluations,id',
        ]);

        $selectedEvaluations = Evaluation::whereIn('id', $request->evaluation_ids)->get();


        foreach ($selectedEvaluations as $evaluation) {
            $evaluation->status = 'processed';
            $evaluation->save();
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'role' => auth()->user()->role,
            'activity' => 'Bulk Update Evaluations',
            'description' => 'Marked ' . count($selectedEvaluations) . ' evaluations as processed.',
            'status' => 'success',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => count($selectedEvaluations) . ' evaluations updated.'
        ]);
    }













public function getPOList(Request $request)
{
    $user = Auth::user();

    $query = PurchaseOrder::query()
        ->where(function ($q) {
            $q->whereNull('status')
              ->orWhere('status', '!=', 'Added');
        });
    /** @var User $user */
    if (!$user->isAdmin()) {

        $office = $user->office?->abbreviation ?? '';

        if ($office === 'PMO') {
            $query->where(function ($q) {
                $q->where('end_user', 'PMO')
                  ->orWhere('end_user', 'LIKE', 'PMO-%');
            });
        } else {
            $query->where('end_user', $office);
        }
    }

    // Optional end user filter
    if ($request->filled('end_user')) {

        if ($request->end_user === 'PMO') {
            $query->where(function ($q) {
                $q->where('end_user', 'PMO')
                  ->orWhere('end_user', 'LIKE', 'PMO-%');
            });
        } else {
            $query->where('end_user', $request->end_user);
        }
    }

    // Supplier filter
    if ($request->filled('supplier')) {
        $query->where('supplier', $request->supplier);
    }

    return response()->json(
        $query->orderBy('po_no')->get()
    );
}


public function getSuppliersByEndUser(Request $request)
{
    $user = auth()->user();

    $query = PurchaseOrder::query()
        ->whereNotNull('supplier')
        ->where('supplier', '!=', '');
    /** @var User $user */
    if (!$user->isAdmin()) {
        $office = $user->office?->abbreviation ?? '';

        if ($office === 'PMO') {
            $query->where(function ($q) {
                $q->where('end_user', 'PMO')
                  ->orWhere('end_user', 'LIKE', 'PMO-%');
            });
        } else {
            $query->where('end_user', $office);
        }
    } elseif ($request->filled('end_user')) {
        // Admin can filter by any end user
        if ($request->end_user === 'PMO') {
            $query->where(function ($q) {
                $q->where('end_user', 'PMO')
                  ->orWhere('end_user', 'LIKE', 'PMO-%');
            });
        } else {
            $query->where('end_user', $request->end_user);
        }
    }

    return response()->json(
        $query->distinct()
              ->orderBy('supplier')
              ->pluck('supplier')
    );
}


    public function storeBulkPOEvaluation(Request $request)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'po_ids' => 'required|array'
            ]);

            $user = auth()->user();

            if (!$user->office_id) {
                throw new \Exception('User has no office assigned.');
            }

            $office = Office::findOrFail($user->office_id);

            $year = now()->year;

            $skipped = [];

            foreach ($request->po_ids as $poId) {

                $po = PurchaseOrder::findOrFail($poId);

                $exists = Evaluation::where('po_no', $po->po_no)
                    ->where('status', '!=', 'deleted')
                    ->exists();

                if ($exists) {
                    $skipped[] = $po->po_no;
                    continue;
                }

                Evaluation::create([
                    'supplier_name'   => $po->supplier,
                    'po_no'           => $po->po_no,
                    'office_id'       => $office->id,
                    'date_evaluation' => now(),
                    'status'          => 'pending',
                    'covered_period'  => 'CY ' . $year,
                    'period_year'     => $year,
                ]);

                $po->update(['status' => 'Added']);

                ActivityLog::create([
                    'user_id' => auth()->id(),
                    'role' => auth()->user()->role,
                    'activity' => 'Bulk PO Evaluation Created',
                    'description' => "Created evaluation for PO No. {$po->po_no}.",
                    'status' => 'success',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bulk evaluation added successfully.',
                'skipped' => $skipped
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }



public function bulkStore(Request $request)
{
    DB::beginTransaction();

    try {

        $user = auth()->user();

        if (empty($request->evaluations)) {
            throw new \Exception("No evaluations submitted.");
        }

        foreach ($request->evaluations as $evalData) {

            $poNo = $evalData['po_no'];


            /** @var User $user */
            if ($user->isEndUser()) {
                $status = 'head review';
            } else {
                $status = 'submitted';
            }


            $evaluation = Evaluation::where('po_no', $poNo)
                ->where('status', '!=', 'deleted')
                ->first();

            if (!$evaluation) {

                $evaluation = Evaluation::create([
                    'supplier_name'   => $evalData['supplier_name'],
                    'po_no'           => $poNo,
                    'date_evaluation' => Carbon::parse($evalData['date_evaluation']),
                    'covered_period'  => 'CY ' . $evalData['year'],
                    'period_year'     => $evalData['year'],
                    'office_id'       => $evalData['office_id'],
                    'status'          => $status,
                ]);


            } else {

                $evaluation->update([
                    'supplier_name'   => $evalData['supplier_name'],
                    'date_evaluation' => Carbon::parse($evalData['date_evaluation']),
                    'office_id'       => $evalData['office_id'],
                    'status'          => $status,
                ]);

                ActivityLog::create([
                    'user_id' => auth()->id(),
                    'role' => auth()->user()->role,
                    'activity' => 'Bulk Evaluation Save',
                    'description' => "Saved evaluation for PO No. {$poNo}.",
                    'status' => 'success',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            }



            foreach ($evalData['criteria'] as $c) {

                $existing = CriteriaScore::where('evaluation_id', $evaluation->id)
                    ->where('criteria_id', $c['criteria_id'])
                    ->first();

                if ($existing) {

                    $existing->update([
                        'number_rating' => $c['rating'] ?? $existing->number_rating,
                        'remarks'       => $c['remarks'] ?? $existing->remarks,
                    ]);

                } else {

                    CriteriaScore::create([
                        'evaluation_id' => $evaluation->id,
                        'criteria_id'   => $c['criteria_id'],
                        'number_rating' => $c['rating'],
                        'remarks'       => $c['remarks'] ?? null,
                    ]);
                }
            }
            ActivityLog::create([
                'user_id' => auth()->id(),
                'role' => auth()->user()->role,
                'activity' => 'Bulk Criteria Update',
                'description' => "Updated criteria scores for PO No. {$poNo}.",
                'status' => 'success',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);




            // PREPARER
            $preparer = User::find($evalData['preparer_id']);

            DigitalApproval::updateOrCreate(
                [
                    'evaluation_id' => $evaluation->id,
                    'role' => 'Prepared by'
                ],
                [
                    'signed_by'   => $preparer?->id,
                    'full_name'   => $preparer?->name,
                    'designation' => $preparer?->designation,
                ]
            );

            ActivityLog::create([
                'user_id' => auth()->id(),
                'role' => auth()->user()->role,
                'activity' => 'Prepared By Updated',
                'description' => "Set preparer for PO No. {$poNo}.",
                'status' => 'success',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);


            if (!empty($evalData['presentative_id'])) {

                $presentative = User::find($evalData['presentative_id']);

                DigitalApproval::updateOrCreate(
                    [
                        'evaluation_id' => $evaluation->id,
                        'role' => 'presentative_staff'
                    ],
                    [
                        'signed_by'   => $presentative?->id,
                        'full_name'   => $presentative?->name,
                        'designation' => $presentative?->designation,
                    ]
                );
            }

            // HEAD APPROVER
            if (!empty($evalData['approver_id']) && !$user->isPresentativeStaff()) {

                $approver = User::find($evalData['approver_id']);

                DigitalApproval::updateOrCreate(
                    [
                        'evaluation_id' => $evaluation->id,
                        'role' => 'Head'
                    ],
                    [
                        'signed_by'   => $approver?->id,
                        'full_name'   => $approver?->name,
                        'designation' => $approver?->designation,
                    ]
                );
                ActivityLog::create([
                    'user_id' => auth()->id(),
                    'role' => auth()->user()->role,
                    'activity' => 'Head Approval Updated',
                    'description' => "Head approval updated for PO No. {$poNo}.",
                    'status' => 'success',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            }
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Saved successfully'
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 400);
    }
}
}
