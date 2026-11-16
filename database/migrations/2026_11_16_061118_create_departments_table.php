<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();

            $table->string('department_name', 100)->unique(); // HR / IT / Finance
            $table->string('department_code', 20)->nullable(); // HR, IT, FIN
            $table->string('description', 255)->nullable();
            $table->tinyInteger('status')->default(1); // 1=active, 0=inactive

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};