<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'employee_code',
        'name',
        'phone',
        'nid_number',
        'photo',
        'gender',
        'date_of_birth',
        'address',
        'department_designation_id',
        'manager_id',
        'shift_id',
        'joining_date',
        'job_type',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'joining_date'  => 'date',
        'gender'        => 'integer',
        'job_type'      => 'integer',
        'status'        => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($employee) {
            if (empty($employee->employee_code)) {
                $year = now()->year;
                $last = self::withTrashed()
                    ->where('employee_code', 'like', "EMP-{$year}-%")
                    ->orderByDesc('id')
                    ->first();
                $nextNumber = 1;
                if ($last) {
                    $parts = explode('-', $last->employee_code);
                    $nextNumber = (int) end($parts) + 1;
                }
                $employee->employee_code = sprintf('EMP-%s-%04d', $year, $nextNumber);
            }
        });
    }
}