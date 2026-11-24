<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'loan_type',
        'amount',
        'loan_date',
        'purpose',
        'lender_name',
        'total_installments',
        'emi_amount',
        'month',
        'year',
        'status',
        'remarks',
    ];

    protected $casts = [
        'loan_date' => 'date',
        'amount' => 'decimal:2',
        'emi_amount' => 'decimal:2',
        'month' => 'integer',
        'year' => 'integer',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Accessor for type label
    public function getLoanTypeLabelAttribute()
    {
        return $this->loan_type === 'long_term' ? 'Long Term' : 'Short Term';
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'paid' => 'Paid',
            default => 'Unknown',
        };
    }
}