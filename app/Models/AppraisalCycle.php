<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppraisalCycle extends Model
{
    protected $fillable = [
        'name', 'cycle_type', 'start_date',
        'end_date', 'review_deadline', 'status'
    ];

    public function appraisals()
    {
        return $this->hasMany(Appraisal::class);
    }
}