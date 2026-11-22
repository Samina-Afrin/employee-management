<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payroll extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'salary_month',
        'per_day_salary',
        'working_days',
        'present_days',
        'absent_days',
        'basic_salary',
        'house_rent',
        'medical_allowance',
        'transport_allowance',
        'other_allowance',
        'gross_salary',
        'tax_percent',
        'tax_deduction',
        'absence_deduction',
        'loan_deduction',
        'advance_amount',
        'installment_months',
        'advance_status',
        'net_salary',
        'payment_status',
        'payment_date',
    ];

    protected $casts = [
        'salary_month'        => 'date',
        'payment_date'        => 'date',
        'per_day_salary'      => 'float',
        'working_days'        => 'integer',
        'present_days'        => 'integer',
        'absent_days'         => 'integer',
        'basic_salary'        => 'float',
        'house_rent'          => 'float',
        'medical_allowance'   => 'float',
        'transport_allowance' => 'float',
        'other_allowance'     => 'float',
        'gross_salary'        => 'float',
        'tax_percent'         => 'float',
        'tax_deduction'       => 'float',
        'absence_deduction'   => 'float',
        'loan_deduction'      => 'float',
        'advance_amount'      => 'float',
        'installment_months'  => 'integer',
        'advance_status'      => 'integer',
        'net_salary'          => 'float',
        'payment_status'      => 'integer',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function getPaymentStatusLabelAttribute()
    {
        return match ($this->payment_status) {
            1 => 'Pending',
            2 => 'Paid',
            default => 'Unknown',
        };
    }

    public function getNetSalaryWordsAttribute()
    {
        return $this->numberToWords($this->net_salary);
    }

    private function numberToWords($number)
    {
        $number = (int) round($number);
        if ($number == 0) return 'Zero Taka Only';

        $ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine',
                 'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen',
                 'Seventeen', 'Eighteen', 'Nineteen'];
        $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

        $words = '';
        if ($number >= 10000000) {
            $words .= $this->numberToWords(intdiv($number, 10000000)) . ' Crore ';
            $number %= 10000000;
        }
        if ($number >= 100000) {
            $words .= $this->numberToWords(intdiv($number, 100000)) . ' Lakh ';
            $number %= 100000;
        }
        if ($number >= 1000) {
            $words .= $this->numberToWords(intdiv($number, 1000)) . ' Thousand ';
            $number %= 1000;
        }
        if ($number >= 100) {
            $words .= $ones[intdiv($number, 100)] . ' Hundred ';
            $number %= 100;
        }
        if ($number > 0) {
            if ($number < 20) {
                $words .= $ones[$number];
            } else {
                $words .= $tens[intdiv($number, 10)];
                if ($number % 10 > 0) {
                    $words .= ' ' . $ones[$number % 10];
                }
            }
        }
        return trim($words) . ' Taka Only';
    }
}