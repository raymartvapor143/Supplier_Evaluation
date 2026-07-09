<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigitalApproval extends Model
{
    use HasFactory;

    protected $table = 'digital_approvals';

    protected $fillable = [
        'evaluation_id',
        'signed_by',
        'authorize_id',
        'full_name',
        'designation',
        'role',
        // 'image',
    ];

    /**
     * Append computed attributes
     */
    protected $appends = ['image_url', 'signature_url'];

    /**
     * ===============================
     * RELATIONSHIPS
     * ===============================
     */

    public function getSignatureUrlAttribute()
{
    if (!$this->signer || !$this->signer->signature) {
        return null;
    }

    return route('signature', $this->signer->id);
}


    // Each digital approval belongs to an evaluation
    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    // Each digital approval belongs to an authorize record
    public function authorize()
    {
        return $this->belongsTo(Authorize::class);
    }

public function signer()
{
    return $this->belongsTo(User::class, 'signed_by');
}

    /**
     * ===============================
     * ACCESSORS
     * ===============================
     */

    // Convert stored image path to full public URL
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        return asset(ltrim($this->image, '/'));
    }
}
