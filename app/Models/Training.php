<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Training extends Model
{
    use SoftDeletes;

    protected $table = 'trainings';

    protected $fillable = [
        'employee_id',
        'training_title',
        'training_type',
        'institute_name',
        'country',
        'start_date',
        'end_date',
        'duration_hours',
        'cost',
        'status',
        'remarks',
    ];

    protected $casts = [
        'start_date'     => 'date',
        'end_date'       => 'date',
        'cost'           => 'decimal:2',
        'status'         => 'integer',
        'duration_hours' => 'integer',
    ];

    public const STATUS_ONGOING   = 1;
    public const STATUS_COMPLETED = 2;
    public const STATUS_CANCELLED = 3;

    public static function statusLabels(): array
    {
        return [
            self::STATUS_ONGOING   => 'Ongoing',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}