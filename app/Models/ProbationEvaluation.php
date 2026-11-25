<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProbationEvaluation extends Model
{
    protected $fillable = [
        'employee_id', 'review_stage',
        'review_date', 'rating',
        'recommendation', 'comments'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}