<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AppraisalCycle extends Model
{
    protected $fillable = [
        'name',
        'cycle_type',
        'start_date',
        'end_date',
        'review_deadline',
        'status',
    ];

    protected $casts = [
        'start_date'      => 'date',
        'end_date'        => 'date',
        'review_deadline' => 'date',
    ];

    public function appraisals(): HasMany
    {
        return $this->hasMany(AppraisalForm::class, 'appraisal_cycle_id');
    }
}