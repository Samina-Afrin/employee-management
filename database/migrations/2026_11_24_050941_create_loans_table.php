
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')
                ->constrained('employees')
                ->cascadeOnDelete();

            $table->enum('loan_type', [
                'long_term',
                'short_term'
            ]);

            $table->decimal('amount', 12, 2);
            $table->date('loan_date');
            $table->string('purpose')->nullable();

            // Long-term loan
            $table->string('lender_name')->nullable();
            $table->integer('total_installments')->nullable();
            $table->decimal('emi_amount', 12, 2)->nullable();

            // Short-term loan
            $table->tinyInteger('month')->nullable();
            $table->year('year')->nullable();

            // Status
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'paid'
            ])->default('pending');

            $table->text('remarks')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
