<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CriteriaScore extends Model
{
    use HasFactory;

    protected $table = 'criteria_scores';

    protected $fillable = [
        'evaluation_id',
        'criteria_id',
        'number_rating',
        'remarks',
    ];

    /**
     * Each score belongs to an evaluation
     */
    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    /**
     * Each score belongs to a criteria
     */
    public function criteria()
    {
        return $this->belongsTo(EvaluationCriteria::class, 'criteria_id');
    }
}
