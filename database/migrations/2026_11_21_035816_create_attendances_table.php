<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('employee_id');
            $table->date('attendance_date');

            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();

            // 1=present, 2=late, 3=absent, 4=leave, 5=holiday
            $table->tinyInteger('status')->default(1);

            $table->tinyInteger('is_manual')->default(0);
            $table->unsignedBigInteger('corrected_by')->nullable();
            $table->string('remarks', 255)->nullable();

            $table->timestamps();
            $table->softDeletes();

            // একই employee এর একই দিনে একটাই attendance
            $table->unique(['employee_id', 'attendance_date']);

            $table->foreign('employee_id')
                  ->references('id')
                  ->on('employees')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};