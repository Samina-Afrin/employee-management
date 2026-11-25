<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeServiceHistory extends Model
{
    
public function up(): void
{
    Schema::create('employee_service_histories', function (Blueprint $table) {
        $table->id();

        $table->foreignId('employee_id')
            ->constrained('employees')
            ->cascadeOnDelete();

        $table->string('event_type');
        $table->string('old_designation')->nullable();
        $table->string('new_designation')->nullable();

        $table->decimal('old_salary', 12, 2)->nullable();
        $table->decimal('new_salary', 12, 2)->nullable();

        $table->date('effective_date');
        $table->text('remarks')->nullable();

        $table->timestamps();
    });
}

}
