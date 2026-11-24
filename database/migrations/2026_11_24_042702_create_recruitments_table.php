<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recruitments', function (Blueprint $table) {
            $table->id();
            $table->string('job_title', 150);
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('designation_id')->nullable();
            $table->integer('total_posts')->default(1);
            $table->text('job_description')->nullable();
            $table->date('last_apply_date');
            $table->tinyInteger('vacancy_status')->default(1)->comment('1=open, 0=closed');

            // Applicant fields
            $table->string('applicant_name', 150)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('cv_path', 255)->nullable();

            // Stage: 1=applied,2=shortlisted,3=interview,4=selected,5=rejected
            $table->tinyInteger('stage')->nullable();
            $table->dateTime('interview_date')->nullable();
            $table->text('interview_note')->nullable();

            // Convert to employee
            $table->unsignedBigInteger('employee_id')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('designation_id')->references('id')->on('designations')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recruitments');
    }
};