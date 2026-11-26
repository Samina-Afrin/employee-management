<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('promotions')) {
            Schema::create('promotions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')
                      ->constrained('employees')
                      ->cascadeOnDelete();
                $table->unsignedBigInteger('old_designation_id')->nullable();
                $table->unsignedBigInteger('new_designation_id')->nullable();
                $table->unsignedBigInteger('old_department_id')->nullable();
                $table->unsignedBigInteger('new_department_id')->nullable();
                $table->decimal('old_salary', 12, 2)->nullable();
                $table->decimal('new_salary', 12, 2)->nullable();
                $table->date('promotion_date');
                $table->string('promotion_type', 50)->default('Promotion'); // Promotion/Increment/Transfer
                $table->string('approved_by', 100)->nullable();
                $table->text('remarks')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index('employee_id');
                $table->index('promotion_date');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};