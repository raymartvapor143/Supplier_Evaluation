<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pdfs', function (Blueprint $table) {
            $table->id();

            // Owner of the PDF
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();


           $table->string('pdf_file')->nullable();

           $table->string('status');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pdfs');
    }
};
