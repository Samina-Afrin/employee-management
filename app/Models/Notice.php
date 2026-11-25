<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'notice_date',
        'expire_date',
        'posted_by',
        'status',
    ];

    protected $casts = [
        'notice_date'  => 'date',
        'expire_date'  => 'date',
        'status'       => 'integer',
    ];

    public function poster()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    /**
     * Active notices (not expired)
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1)
            ->where(function ($q) {
                $q->whereNull('expire_date')
                  ->orWhere('expire_date', '>=', now()->toDateString());
            });
    }
}