<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $table = 'evaluations';

    protected $fillable = [
        'supplier_name',
        'po_no',
        'date_evaluation',
        'office_id',
        'covered_period',
        'period_year',
        'status',
        'delete_status',
    ];

    protected $casts = [
        'date_evaluation' => 'date',
        'period_year'     => 'integer',
    ];

    // =========================
    // RELATIONSHIPS
    // =========================

    public function criteriaScores()
    {
        return $this->hasMany(CriteriaScore::class);
    }

    public function digitalApprovals()
    {
        return $this->hasMany(DigitalApproval::class);
    }

    public function requests()
    {
        return $this->hasMany(Requests::class);
    }

    public function evaluationLink()
    {
        return $this->hasOne(EvaluationLink::class);
    }

    public function latestRequest()
    {
        return $this->hasOne(Requests::class, 'evaluation_id')
            ->latestOfMany('request_date');
    }

    public function authorizes()
{
    return $this->hasMany(Authorize::class);
}

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_no', 'po_no');
    }

    // =========================
    // SCOPES
    // =========================

    public function scopeForUser($query, $user)
    {
        if (!$user->isAdmin() && $user->role !== 'pgso') {
            $query->where('office_id', $user->office_id);
        }

        return $query;
    }

public function scopeNotDeleted($query)
{
    return $query->where(function ($q) {
        $q->where('delete_status', 0)
          ->orWhereNull('delete_status');
    });
}
}
