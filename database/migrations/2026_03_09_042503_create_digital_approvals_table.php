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
Schema::create('digital_approvals', function (Blueprint $table) {
    $table->id();

    $table->foreignId('evaluation_id')
        ->constrained('evaluations')
        ->onDelete('cascade');

    $table->foreignId('signed_by')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->foreignId('authorize_id')
        ->constrained('authorizes')
        ->cascadeOnDelete()->nullable();

    $table->string('full_name')->nullable();
    $table->string('designation')->nullable();
    $table->string('role')->nullable();
    $table->string('image')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digital_approvals');
    }
};
