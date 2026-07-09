<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $table = 'purchase_orders';

    protected $fillable = [
        'po_no',
        'pr_no',
        'item',
        'pdf_po',
        'end_user',
        'supplier',
        'status',
    ];


public function evaluation()
{
    return $this->hasOne(Evaluation::class, 'po_no', 'po_no')
        ->latestOfMany();
}

}



