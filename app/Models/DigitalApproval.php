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
    ];

    /**
     * Append computed attributes.
     */
    protected $appends = [
        'image_url',
        'signature_url',
    ];

    /**
     * ===============================
     * RELATIONSHIPS
     * ===============================
     */

    // Approval belongs to an evaluation
    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    // Approval belongs to an authorize record
    public function authorize()
    {
        return $this->belongsTo(Authorize::class);
    }

    // User who signed the approval
    public function signer()
    {
        return $this->belongsTo(User::class, 'signed_by');
    }

    /**
     * ===============================
     * ACCESSORS
     * ===============================
     */

    public function getSignatureUrlAttribute()
    {
        if (!$this->signer || !$this->signer->signature) {
            return null;
        }

        return route('signature', $this->signer->id);
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        return asset(ltrim($this->image, '/'));
    }
}
