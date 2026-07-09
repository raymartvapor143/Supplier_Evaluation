<?php

namespace App\Imports;

use App\Models\PurchaseOrder;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PurchaseOrdersImport implements ToModel, WithHeadingRow
{
    public array $duplicates = [];
    public int $inserted = 0;

    public function model(array $row)
    {
        $poNo = trim($row['po_no']);

        // check duplicate
        $exists = PurchaseOrder::where('po_no', $poNo)->exists();

        if ($exists) {
            $this->duplicates[] = $poNo;
            return null;
        }

        $this->inserted++;

        return new PurchaseOrder([
            'po_no'    => $poNo,
            'pr_no'    => $row['pr_no'] ?? null, // ✅ FIXED
            'end_user' => strtoupper(trim($row['end_user'])),
            'supplier' => trim($row['supplier']),
            'status'   => 'Pending',
        ]);
    }
}
