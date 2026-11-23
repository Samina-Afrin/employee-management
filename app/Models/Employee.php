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
        'department_id',
        'designation_id',
        'phone',
        'nid_number',
        'photo',
        'gender',
        'date_of_birth',
        'address',
        'manager_id',
        'shift_id',
        'joining_date',
        'job_type',
        'status',
        'monthly_salary',
        'salary_type',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'joining_date'  => 'date',
        'gender'        => 'integer',
        'job_type'      => 'integer',
        'status'        => 'integer',
        'monthly_salary' => 'decimal:2',
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

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }
}