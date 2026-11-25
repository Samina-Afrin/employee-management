
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appraisal_cycles', function (Blueprint $table) {
            $table->id();

            $table->string('name', 200);

            $table->enum('cycle_type', [
                'yearly',
                'half_yearly'
            ]);

            $table->date('start_date');
            $table->date('end_date');
            $table->date('review_deadline')->nullable();

            $table->enum('status', [
                'draft',
                'active',
                'completed'
            ])->default('draft');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appraisal_cycles');
    }
};
