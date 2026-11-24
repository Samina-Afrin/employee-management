<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recruitment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'job_title', 'department_id', 'designation_id',
        'total_posts', 'job_description', 'last_apply_date',
        'vacancy_status', 'applicant_name', 'phone', 'email',
        'cv_path', 'stage', 'interview_date', 'interview_note',
        'employee_id',
    ];

    protected $casts = [
        'last_apply_date' => 'date',
        'interview_date'  => 'datetime',
        'vacancy_status'  => 'integer',
        'stage'           => 'integer',
    ];

    public const STAGES = [
        1 => 'Applied',
        2 => 'Shortlisted',
        3 => 'Interview',
        4 => 'Selected',
        5 => 'Rejected',
    ];

    public function getStageLabelAttribute()
    {
        return self::STAGES[$this->stage] ?? '—';
    }

    public function getVacancyStatusLabelAttribute()
    {
        return $this->vacancy_status == 1 ? 'Open' : 'Closed';
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}