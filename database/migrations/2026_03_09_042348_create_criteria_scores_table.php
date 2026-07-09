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
        Schema::create('criteria_scores', function (Blueprint $table) {
            $table->id(); // ID (PK)

            // FK → evaluations.id
            $table->foreignId('evaluation_id')
                  ->constrained('evaluations')
                  ->onDelete('cascade');

            // FK → evaluation_criteria.id
            $table->foreignId('criteria_id')
                  ->constrained('evaluation_criteria')
                  ->onDelete('cascade');

            $table->integer('number_rating')->nullable();
            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criteria_scores');
    }
};
