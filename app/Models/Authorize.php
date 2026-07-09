<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Authorize extends Model
{
    protected $table = 'authorizes';

    protected $fillable = [
        'pdf_id',
        'evaluation_id',




    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }
    public function pdf()
{
    return $this->belongsTo(Pdf::class, 'pdf_id');
}

    // Accessor for PDF status
    public function getStatusAttribute()
    {
        return $this->pdf ? $this->pdf->status : null;
    }
}
