<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('employee_id');

            // 1=casual, 2=sick, 3=annual, 4=festival, 5=maternity, 6=paternity, 7=unpaid, 8=compensatory
            $table->tinyInteger('leave_type')->default(1);

            $table->integer('leave_year');
            $table->decimal('yearly_limit_days', 5, 1)->default(0);

            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_days', 5, 1)->default(0);

            $table->string('reason', 255)->nullable();

            // 1=pending, 2=approved, 3=rejected
            $table->tinyInteger('status')->default(1);

            $table->unsignedBigInteger('approved_by')->nullable();
            $table->string('decision_note', 255)->nullable();

            $table->decimal('remaining_balance', 5, 1)->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('employee_id')
                  ->references('id')
                  ->on('employees')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};