<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('performance_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->year('review_year');
            $table->enum('period', ['jan_dec', 'jul_jun'])->default('jan_dec');
            $table->decimal('rating', 3, 2)->default(0);
            $table->text('manager_comment')->nullable();
            $table->enum('status', ['draft', 'submitted', 'approved'])->default('draft');
            $table->date('review_date')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'review_year', 'period']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('performance_reviews');
    }
};