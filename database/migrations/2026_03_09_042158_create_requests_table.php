<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();

            // 👇 evaluation link
            $table->foreignId('evaluation_id')
                ->constrained('evaluations')
                ->onDelete('cascade');

            // 👤 USER RELATIONSHIP (REQUESTOR)
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            // 🎯 OPTIONAL: WHO IT IS REQUESTED TO (PGSO / ADMIN / HEAD)
            $table->foreignId('requested_to')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('reason');
            $table->string('status');

            $table->timestamp('request_date')->nullable();
            $table->timestamp('status_date')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
