<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Crypt;

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

    protected $appends = ['encrypted_id'];

    public function getEncryptedIdAttribute()
    {
        return Crypt::encryptString((string)$this->id);
    }

    public function evaluation()
    {
        return $this->hasOne(Evaluation::class, 'po_no', 'po_no')
            ->latestOfMany();
    }
}



