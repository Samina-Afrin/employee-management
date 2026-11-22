<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('employee_id');
            $table->date('salary_month');

            // Per-Day Salary Base
            $table->decimal('per_day_salary', 12, 2)->default(0);
            $table->integer('working_days')->default(30);
            $table->integer('present_days')->default(30);
            $table->integer('absent_days')->default(0);

            // Earnings
            $table->decimal('basic_salary', 12, 2)->default(0);
            $table->decimal('house_rent', 12, 2)->default(0);
            $table->decimal('medical_allowance', 12, 2)->default(0);
            $table->decimal('transport_allowance', 12, 2)->default(0);
            $table->decimal('other_allowance', 12, 2)->default(0);
            $table->decimal('gross_salary', 12, 2)->default(0);

            // Deductions
            $table->decimal('tax_percent', 5, 2)->default(0);
            $table->decimal('tax_deduction', 12, 2)->default(0);
            $table->decimal('absence_deduction', 12, 2)->default(0);
            $table->decimal('loan_deduction', 12, 2)->default(0);

            // Advance / Loan
            $table->decimal('advance_amount', 12, 2)->default(0);
            $table->integer('installment_months')->nullable();
            $table->tinyInteger('advance_status')->default(0);

            // Net
            $table->decimal('net_salary', 12, 2)->default(0);

            // Payment
            $table->tinyInteger('payment_status')->default(1);
            $table->date('payment_date')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['employee_id', 'salary_month']);

            $table->foreign('employee_id')
                  ->references('id')
                  ->on('employees')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};