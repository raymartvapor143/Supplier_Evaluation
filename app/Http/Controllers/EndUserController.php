<?php

namespace App\Http\Controllers;

use App\Models\Pdf;
use App\Models\PurchaseOrder;
use App\Models\Requests;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EndUserController extends Controller
{
public function dashboard()
{
    $user = auth()->user();

    // Admin/PGSO users for request dropdown
    $users = User::query()
        ->whereIn('role', ['administrator', 'pgso'])
        ->where('status', 'active')
        ->orderBy('name')
        ->get();


    $poQuery = PurchaseOrder::query();
    /** @var User $user */
    if (!$user->isAdmin()) {
        $poQuery->where('end_user', $user->office->abbreviation);
    }

    // Purchase Orders
    $pos = (clone $poQuery)
        ->latest()
        ->get();

    // End User list
    $endUsers = (clone $poQuery)
        ->whereNotNull('end_user')
        ->where('end_user', '!=', '')
        ->distinct()
        ->orderBy('end_user')
        ->pluck('end_user');

    // Supplier list
    $suppliers = (clone $poQuery)
        ->whereNotNull('supplier')
        ->where('supplier', '!=', '')
        ->distinct()
        ->orderBy('supplier')
        ->pluck('supplier');


    $requestMessages = Requests::with('evaluation')
        ->where('user_id', auth()->id())
        ->where('status', '!=', 'request')
        ->latest()
        ->get()
        ->map(function ($request) {
            return (object)[
                'type'       => 'request',
                'po_no'      => $request->evaluation->po_no ?? 'No PO Number',
                'status'     => $request->status,
                'created_at' => $request->created_at,
            ];
        });


    $pdfMessages = Pdf::query()
        ->where('user_id', auth()->id())
        ->where('status', 'approved')
        ->latest()
        ->get()
        ->map(function ($pdf) {
            return (object)[
                'type'       => 'pdf',
                'po_no'      => 'PDF Document',
                'status'     => 'approved',
                'created_at' => $pdf->created_at,
            ];
        });


    $messages = collect($requestMessages)
        ->merge($pdfMessages)
        ->sortByDesc('created_at')
        ->values()
        ->take(10);

    $messageCount = $messages->count();

    return view('users.dashboard', compact(
        'users',
        'pos',
        'messages',
        'messageCount',
        'endUsers',
        'suppliers'
    ));
}
}
