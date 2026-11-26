<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Promotion extends Model
{
    use SoftDeletes;

    protected $table = 'promotions';

    protected $fillable = [
        'employee_id',
        'old_designation_id',
        'new_designation_id',
        'old_department_id',
        'new_department_id',
        'old_salary',
        'new_salary',
        'promotion_date',
        'promotion_type',
        'approved_by',
        'remarks',
    ];

    protected $casts = [
        'promotion_date' => 'date',
        'old_salary'     => 'decimal:2',
        'new_salary'     => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function oldDesignation(): BelongsTo
    {
        return $this->belongsTo(Designation::class, 'old_designation_id');
    }

    public function newDesignation(): BelongsTo
    {
        return $this->belongsTo(Designation::class, 'new_designation_id');
    }

    public function oldDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'old_department_id');
    }

    public function newDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'new_department_id');
    }
}