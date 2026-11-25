<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $fillable = [
        'employee_id', 'title', 'description', 'kpi',
        'target_value', 'achieved_value', 'progress',
        'start_date', 'due_date', 'status'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}