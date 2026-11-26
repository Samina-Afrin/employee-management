<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppraisalForm extends Model
{
    protected $table = 'appraisal_forms';

    protected $fillable = [
        'employee_id',
        'appraisal_cycle_id',
        'self_rating',
        'self_comments',
        'manager_rating',
        'manager_comments',
        'hr_rating',
        'hr_comments',
        'final_rating',
        'status',
    ];

    protected $casts = [
        'self_rating'    => 'decimal:2',
        'manager_rating' => 'decimal:2',
        'hr_rating'      => 'decimal:2',
        'final_rating'   => 'decimal:2',
    ];

    // Employee relationship
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    // Appraisal Cycle relationship
    public function cycle(): BelongsTo
    {
        return $this->belongsTo(AppraisalCycle::class, 'appraisal_cycle_id');
    }
}