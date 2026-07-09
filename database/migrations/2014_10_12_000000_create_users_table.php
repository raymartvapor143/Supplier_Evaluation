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
        // Users table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('office_id')
                ->nullable()
                ->constrained('offices')
                ->nullOnDelete();
            $table->string('designation');
            $table->enum('status', ['active', 'inactive', 'suspended', 'rejected', 'freeze'])->default('inactive'); // new
            $table->enum('role', ['end_user', 'administrator','pgso','head'])->default('end_user'); // new
            $table->longText('signature')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
