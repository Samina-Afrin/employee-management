<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('designations', function (Blueprint $table) {
            $table->id();

            // কোন department-এর designation
            $table->unsignedBigInteger('department_id')->nullable();

            $table->string('designation_name', 100); // Manager / Officer / Developer
            $table->string('designation_code', 20)->nullable(); // MGR / OFF / DEV
            $table->string('description', 255)->nullable();
            $table->tinyInteger('status')->default(1); // 1=active, 0=inactive

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('department_id')
                  ->references('id')
                  ->on('departments')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('designations');
    }
};