<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('authorizes', function (Blueprint $table) {
            $table->id();

            // 🔥 NEW: relation to pdfs table
            $table->foreignId('pdf_id')
                  ->constrained('pdfs')
                  ->cascadeOnDelete();


            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('authorizes');
    }
};
