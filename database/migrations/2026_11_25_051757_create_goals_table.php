
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
        Schema::create('goals', function (Blueprint $table) {
            $table->id();

            // Employee relationship
            $table->foreignId('employee_id')
                ->constrained('employees')
                ->cascadeOnDelete();

            // Goal information
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('kpi')->nullable();

            // Target and achievement
            $table->decimal('target_value', 10, 2)->nullable();
            $table->decimal('achieved_value', 10, 2)->default(0);

            // Goal progress (0-100%)
            $table->unsignedTinyInteger('progress')->default(0);

            // Goal duration
            $table->date('start_date');
            $table->date('due_date');

            // Goal status
            $table->enum('status', [
                'pending',
                'in_progress',
                'completed',
                'cancelled'
            ])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goals');
    }
};
