<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyAsset extends Model
{
    use SoftDeletes;

    protected $table = 'company_assets';

    protected $fillable = [
        'asset_code',
        'asset_name',
        'category',
        'serial_number',
        'employee_id',
        'given_date',
        'return_date',
        'status',
        'note',
    ];

    protected $casts = [
        'given_date'  => 'date',
        'return_date' => 'date',
        'status'      => 'integer',
    ];

    
    public const STATUS_AVAILABLE = 1;
    public const STATUS_ASSIGNED  = 2;
    public const STATUS_DAMAGED   = 3;

    public static function statusLabels(): array
    {
        return [
            self::STATUS_AVAILABLE => 'Available',
            self::STATUS_ASSIGNED  => 'Assigned',
            self::STATUS_DAMAGED   => 'Damaged',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    
    public function isAssigned(): bool
    {
        return $this->status === self::STATUS_ASSIGNED && $this->employee_id !== null;
    }
}