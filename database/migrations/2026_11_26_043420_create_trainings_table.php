<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('trainings')) {
            Schema::create('trainings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')
                      ->constrained('employees')
                      ->cascadeOnDelete();
                $table->string('training_title', 150);
                $table->string('training_type', 50)->default('Internal');
                $table->string('institute_name', 150)->nullable();
                $table->string('country', 50)->nullable()->default('Bangladesh');
                $table->date('start_date');
                $table->date('end_date')->nullable();
                $table->integer('duration_hours')->nullable();
                $table->decimal('cost', 10, 2)->nullable();
                $table->tinyInteger('status')->default(1); // 1=ongoing,2=completed,3=cancelled
                $table->text('remarks')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index('employee_id');
                $table->index('status');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};