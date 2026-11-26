<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('company_assets')) {
            Schema::create('company_assets', function (Blueprint $table) {
                $table->id();
                $table->string('asset_code', 50)->unique();
                $table->string('asset_name', 150);
                $table->string('category', 50);
                $table->string('serial_number', 100)->nullable();
                $table->foreignId('employee_id')
                      ->nullable()
                      ->constrained('employees')
                      ->nullOnDelete();
                $table->date('given_date')->nullable();
                $table->date('return_date')->nullable();
                $table->tinyInteger('status')->default(1);
                $table->string('note', 255)->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index('status');
                $table->index('category');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('company_assets');
    }
};