<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'attendance_date',
        'check_in',
        'check_out',
        'status',
        'is_manual',
        'corrected_by',
        'remarks',
    ];

    protected $casts = [
    'status'    => 'integer',
    'is_manual' => 'integer',
];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function corrector()
    {
        return $this->belongsTo(User::class, 'corrected_by');
    }
}