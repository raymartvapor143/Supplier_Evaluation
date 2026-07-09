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
    Schema::table('authorizes', function (Blueprint $table) {

        $table->foreignId('evaluation_id')
            ->nullable()
            ->after('id')
            ->constrained('evaluations')
            ->cascadeOnDelete();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('authorizes', function (Blueprint $table) {
            //
        });
    }
};
