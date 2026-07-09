<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationCriteria extends Model
{
    use HasFactory;

    protected $table = 'evaluation_criteria';

    protected $fillable = [
        'criteria_name',
    ];

public function criteriaScores()
{
    return $this->hasMany(CriteriaScore::class, 'criteria_id');
}
}
