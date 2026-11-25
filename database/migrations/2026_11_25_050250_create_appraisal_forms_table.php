<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appraisal_forms', function (Blueprint $table) {
            $table->id();

            // Employee relationship
            $table->foreignId('employee_id')
                ->constrained('employees')
                ->cascadeOnDelete();

            // Appraisal cycle relationship
            $table->foreignId('appraisal_cycle_id')
                ->constrained('appraisal_cycles')
                ->cascadeOnDelete();

            // Employee self appraisal
            $table->decimal('self_rating', 3, 1)->nullable();
            $table->text('self_comments')->nullable();

            // Manager appraisal
            $table->decimal('manager_rating', 3, 1)->nullable();
            $table->text('manager_comments')->nullable();

            // HR appraisal
            $table->decimal('hr_rating', 3, 1)->nullable();
            $table->text('hr_comments')->nullable();

            // Final rating
            $table->decimal('final_rating', 3, 1)->nullable();

            // Appraisal status
            $table->enum('status', [
                'draft',
                'submitted',
                'manager_review',
                'hr_review',
                'completed'
            ])->default('draft');

            $table->timestamps();

            // One employee can have only one form per appraisal cycle
            $table->unique(
                ['employee_id', 'appraisal_cycle_id'],
                'appraisal_forms_employee_cycle_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appraisal_forms');
    }
};