<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('evaluation_links', function (Blueprint $table) {
            $table->id();

            $table->foreignId('evaluation_id')
                  ->constrained('evaluations')
                  ->onDelete('cascade');

            // Secure unique token (used in URL)
            $table->string('token')->unique();
            $table->string('code')->unique();

            // Link control
            $table->boolean('is_used')->default(false);
            $table->timestamp('expires_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluation_links');
    }
};
