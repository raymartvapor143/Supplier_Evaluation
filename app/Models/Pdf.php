<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pdf extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pdf_file',
         'status',
    ];

    /**
     * A PDF belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


public function office()
{
    return $this->belongsTo(Office::class);
}
}
