<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Designation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'designation_name',
        'designation_code',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
        'department_id' => 'integer',
    ];

    /**
     * Designation-এর Department
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}