<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareerRecommendation extends Model
{
    
public function up(): void
{
    Schema::create('career_recommendations', function (Blueprint $table) {
        $table->id();

        $table->foreignId('employee_id')
            ->constrained('employees')
            ->cascadeOnDelete();

        $table->enum('type', ['increment', 'promotion']);

        $table->decimal('current_salary', 12, 2)->nullable();
        $table->decimal('proposed_salary', 12, 2)->nullable();

        $table->string('current_designation')->nullable();
        $table->string('proposed_designation')->nullable();

        $table->date('effective_date');
        $table->text('reason')->nullable();

        $table->enum('status', [
            'pending', 'approved', 'rejected'
        ])->default('pending');

        $table->foreignId('approved_by')
            ->nullable()->constrained('users')
            ->nullOnDelete();

        $table->timestamp('approved_at')->nullable();

        $table->timestamps();
    });
}

}
