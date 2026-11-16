<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('employee_code', 50)->unique();
            $table->string('name', 150);
            $table->string('phone', 20)->nullable();

            $table->string('nid_number', 30)->nullable()->unique();
            $table->string('photo', 255)->nullable();

            $table->tinyInteger('gender')->default(1);

            $table->date('date_of_birth')->nullable();
            $table->text('address')->nullable();

            $table->unsignedBigInteger('department_designation_id')->nullable();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->unsignedBigInteger('shift_id')->nullable();

            $table->date('joining_date');
            $table->tinyInteger('job_type')->default(1);
            $table->tinyInteger('status')->default(1);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};