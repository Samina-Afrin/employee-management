<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'department_name',
        'department_code',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    /**
     * Department-এর সব Employee
     * employees.department_designation_id → departments.id
     */
    public function employees()
    {
        return $this->hasMany(Employee::class, 'department_designation_id', 'id');
    }
}