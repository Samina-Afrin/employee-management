<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leave extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'leave_type',
        'leave_year',
        'yearly_limit_days',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'status',
        'approved_by',
        'decision_note',
        'remaining_balance',
    ];

    protected $casts = [
    'leave_type'         => 'integer',
    'leave_year'         => 'integer',
    'yearly_limit_days'  => 'float',
    'total_days'         => 'float',
    'status'             => 'integer',
    'remaining_balance'  => 'float',
    'start_date'         => 'date',
    'end_date'           => 'date',
];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getLeaveTypeLabelAttribute()
    {
        return match ($this->leave_type) {
            1 => 'Casual',
            2 => 'Sick',
            3 => 'Annual',
            4 => 'Festival',
            5 => 'Maternity',
            6 => 'Paternity',
            7 => 'Unpaid',
            8 => 'Compensatory',
            default => 'Unknown',
        };
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            1 => 'Pending',
            2 => 'Approved',
            3 => 'Rejected',
            default => 'Unknown',
        };
    }

    public static function defaultLimit($leaveType)
    {
        return match ((int) $leaveType) {
            1 => 10,   // Casual
            2 => 14,   // Sick
            3 => 18,   // Annual
            4 => 11,   // Festival
            5 => 112,  // Maternity (16 weeks)
            6 => 5,    // Paternity
            default => 0,
        };
    }
}