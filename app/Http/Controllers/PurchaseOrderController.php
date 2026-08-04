<?php

namespace App\Http\Controllers;

use App\Imports\PurchaseOrdersImport;
use App\Models\ActivityLog;
use App\Models\Evaluation;
use App\Models\Office;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{

// public function index()
// {
//     $user = Auth::user();

//     $pos = PurchaseOrder::with('evaluation')
//         ->where('end_user', $user->office->abbreviation)
//         ->latest()
//         ->get();

//     return view('purchase_orders.index', compact('pos'));
// }


public function store(Request $request)
{
    $request->validate([
        'po_no'    => 'required|unique:purchase_orders,po_no',
        'pr_no'    => 'nullable',
        'end_user' => 'required',
        'supplier' => 'required',
        'pdf_po'   => 'nullable|mimes:pdf|max:10240',
    ], [
        'pdf_po.mimes' => 'Only PDF files are allowed.',
        'pdf_po.max'   => 'The PDF must not exceed 10 MB.',
    ]);

    $po = PurchaseOrder::create([
        'po_no'    => $request->po_no,
        'pr_no'    => $request->pr_no,
        'end_user' => $request->end_user,
        'supplier' => $request->supplier,
        'status'   => 'Pending',
    ]);


    if ($request->hasFile('pdf_po')) {
        $file = $request->file('pdf_po');
        $scanner = new \App\Services\FileSecurityScanner();
        $scanResult = $scanner->scanUploadedFile($file);
        if (!$scanResult['safe']) {
            return response()->json([
                'message' => 'Security Threat Blocked: ' . $scanResult['reason']
            ], 422);
        }

        $filename = time() . '_' . $po->po_no . '.pdf';

        $path = $file->storeAs(
            'private/po_pdf',
            $filename,
            'local'
        );

        $po->update([
            'pdf_po' => $path,
        ]);
    }

    ActivityLog::create([
        'user_id'     => auth()->id(),
        'role'        => auth()->user()->role,
        'activity'    => 'Create Purchase Order',
        'description' => "Created Purchase Order {$po->po_no}",
        'status'      => 'success',
        'ip_address'  => $request->ip(),
        'user_agent'  => $request->userAgent(),
    ]);

    return back()->with('po_success', 'Purchase Order added successfully.');
}

public function import(Request $request)
{
    try {
        $import = new PurchaseOrdersImport;

        Excel::import($import, $request->file('file'));

        $inserted = $import->inserted;
        $duplicates = $import->duplicates;
        $notImported = count($duplicates);

        return response()->json([
            'success' => true,
            'message' => 'Import completed successfully!',
            'inserted_count' => $inserted,
            'duplicate_count' => $notImported,
            'duplicates' => $duplicates
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}


    public function destroy($id)
    {
        $po = PurchaseOrder::findOrFail($id);
        $po->delete();

        return back()->with(
            'po_deleted',
            'The purchase order was successfully deleted.'
        );
    }




public function storePOEvaluation(Request $request, $poId)
{
    DB::beginTransaction();

    try {
        $user = auth()->user();

        $request->validate([
            'supplier_name' => 'required',
            'po_no'         => 'required',
        ]);

        $po = PurchaseOrder::findOrFail($poId);


        $exists = Evaluation::where('po_no', $request->po_no)
            ->where(function ($q) {
                $q->where('delete_status', 0)
                  ->orWhereNull('delete_status');
            })
            ->exists();

        if ($exists) {
            throw new \Exception("PO {$request->po_no} already exists in evaluation list.");
        }

        $office = Office::findOrFail($user->office_id);
        $year = now()->year;


        $evaluation = Evaluation::create([
            'supplier_name'   => $request->supplier_name,
            'po_no'           => $request->po_no,
            'office_id'       => $office->id,
            'date_evaluation' => now(),
            'status'          => 'pending',
            'covered_period'  => 'CY ' . $year,
            'period_year'     => $year,
        ]);


        $po->update([
            'status' => 'Added'
        ]);


        ActivityLog::create([
            'user_id'     => $user->id,
            'role'        => $user->role,
            'activity'    => 'Create Evaluation from PO',
            'description' => "Created evaluation for PO {$po->po_no} ({$request->supplier_name}) and marked PO as Added.",
            'status'      => 'success',
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        DB::commit();

        return back()->with('po_success_added', 'Evaluation saved successfully.');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()->with('error', $e->getMessage());
    }
}


public function countPurchaseOrders(Request $request)
{
    $count = \App\Models\PurchaseOrder::count();

    return response()->json([
        'count' => $count
    ]);
}


public function update(Request $request, $id)
{
    $po = PurchaseOrder::findOrFail($id);

    $request->merge([
        'po_no' => trim($request->po_no),
        'pr_no' => $request->filled('pr_no')
            ? trim($request->pr_no)
            : null,
    ]);

    // Check if anything changed
    $hasChanges =
        $po->po_no !== $request->po_no ||
        $po->pr_no !== $request->pr_no ||
        $po->supplier !== $request->supplier ||
        $po->end_user !== $request->end_user ||
        $po->status !== $request->status ||
        $request->hasFile('pdf_po') ||
        $request->remove_pdf == "1";

    if (!$hasChanges) {
        return back()->with('po_error_update', 'No changes detected.');
    }

    $request->validate([
        'po_no' => [
            'required',
            Rule::unique('purchase_orders', 'po_no')->ignore($id),
        ],
        'pr_no'    => 'nullable|string|max:255',
        'supplier' => 'required|string|max:255',
        'end_user' => 'required|string|max:255',
        'status'   => 'required|in:Pending,Added,Approved,Cancelled',
        'pdf_po'   => 'nullable|file|mimes:pdf|max:15240',
    ], [
        'po_no.unique' => 'This PO Number already exists.',
    ]);

    DB::beginTransaction();

    try {

        $oldStatus = $po->status;

        $pdfUpdated = false;
        $pdfRemoved = false;

        // Update fields
        $po->po_no = $request->po_no;
        $po->pr_no = $request->pr_no;
        $po->supplier = $request->supplier;
        $po->end_user = $request->end_user;
        $po->status = $request->status;

        /*
        |--------------------------------------------------------------------------
        | Remove Existing PDF
        |--------------------------------------------------------------------------
        */
        if ($request->remove_pdf == "1") {

            if ($po->pdf_po && Storage::disk('local')->exists($po->pdf_po)) {
                Storage::disk('local')->delete($po->pdf_po);
            }

            $po->pdf_po = null;
            $pdfRemoved = true;
        }

        /*
        |--------------------------------------------------------------------------
        | Upload / Replace PDF
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('pdf_po')) {
            $file = $request->file('pdf_po');
            $scanner = new \App\Services\FileSecurityScanner();
            $scanResult = $scanner->scanUploadedFile($file);
            if (!$scanResult['safe']) {
                return back()->with('po_error_update', 'Security Threat Blocked: ' . $scanResult['reason']);
            }

            // Delete previous PDF if it still exists
            if ($po->pdf_po && Storage::disk('local')->exists($po->pdf_po)) {
                Storage::disk('local')->delete($po->pdf_po);
            }

            $filename = time() . '_' .
                preg_replace('/[^A-Za-z0-9_-]/', '_', $po->po_no) .
                '.pdf';

            $path = $file->storeAs(
                'private/po_pdf',
                $filename,
                'local'
            );

            $po->pdf_po = $path;
            $pdfUpdated = true;
            $pdfRemoved = false;
        }

        $po->save();

        /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */
        $changes = [];

        if ($oldStatus !== $po->status) {
            $changes[] = "Status: {$oldStatus} → {$po->status}";
        }

        if ($pdfUpdated) {
            $changes[] = "PDF replaced";
        } elseif ($pdfRemoved) {
            $changes[] = "PDF removed";
        }

        ActivityLog::create([
            'user_id'     => auth()->id(),
            'role'        => auth()->user()->role,
            'activity'    => 'Update Purchase Order',
            'description' => empty($changes)
                ? "Updated PO {$po->po_no}"
                : "Updated PO {$po->po_no} (" . implode(', ', $changes) . ")",
            'status'      => 'success',
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        DB::commit();

        return back()->with(
            'po_updated',
            'Purchase Order updated successfully.'
        );

    } catch (\Throwable $e) {

        DB::rollBack();

        Log::error('PO UPDATE FAILED', [
            'message' => $e->getMessage(),
            'trace'   => $e->getTraceAsString(),
        ]);

        return back()->with(
            'po_error_update',
            'Update failed: ' . $e->getMessage()
        );
    }
}


public function uploadPdf(Request $request, $id)
{
    try {

        $request->validate([
            'pdf_po' => 'required|mimes:pdf|max:30240',
        ], [
            'pdf_po.required' => 'Please select a PDF file.',
            'pdf_po.mimes'    => 'Only PDF files are allowed.',
            'pdf_po.max'      => 'The PDF must not exceed 30 MB.',
        ]);

        $po = PurchaseOrder::findOrFail($id);

        if ($request->hasFile('pdf_po')) {
            $file = $request->file('pdf_po');

            $scanner = new \App\Services\FileSecurityScanner();
            $scanResult = $scanner->scanUploadedFile($file);
            if (!$scanResult['safe']) {
                return back()->with('error_pdf', 'Security Threat Blocked: ' . $scanResult['reason']);
            }

            // delete old file safely
            if ($po->pdf_po && Storage::disk('local')->exists($po->pdf_po)) {
                Storage::disk('local')->delete($po->pdf_po);
            }

            $filename = time() . '_' . $po->po_no . '.pdf';

            $path = $file->storeAs(
                'private/po_pdf',
                $filename,
                'local'
            );

            $po->update([
                'pdf_po' => $path
            ]);

            ActivityLog::create([
                'user_id'     => auth()->id(),
                'role'        => auth()->user()->role,
                'activity'    => 'Upload Purchase Order PDF',
                'description' => "Uploaded PDF for PO {$po->po_no}",
                'status'      => 'success',
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
            ]);
        }

        return back()->with(
            'success_pdf',
            'Purchase Order PDF uploaded successfully.'
        );

    } catch (\Illuminate\Validation\ValidationException $e) {

        return back()->with('error_pdf', $e->getMessage());

    } catch (\Exception $e) {

        return back()->with(
            'error_pdf',
            'Something went wrong. Please try again.'
        );
    }
}

public function viewPdf($id)
{
    $po = PurchaseOrder::findOrFail($id);

    if (!$po->pdf_po || !Storage::disk('local')->exists($po->pdf_po)) {
        abort(404);
    }

    return response()->file(storage_path('app/' . $po->pdf_po));
}
}
