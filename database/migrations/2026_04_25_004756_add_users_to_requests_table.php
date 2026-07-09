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
    Schema::table('requests', function (Blueprint $table) {

        if (!Schema::hasColumn('requests', 'user_id')) {
            $table->foreignId('user_id')
                ->after('evaluation_id')
                ->constrained('users')
                ->cascadeOnDelete();
        }

        if (!Schema::hasColumn('requests', 'requested_to')) {
            $table->foreignId('requested_to')
                ->nullable()
                ->after('user_id')
                ->constrained('users')
                ->nullOnDelete();
        }
    });
}

public function down(): void
{
    Schema::table('requests', function (Blueprint $table) {
        $table->dropForeign(['user_id']);
        $table->dropForeign(['requested_to']);

        $table->dropColumn(['user_id', 'requested_to']);
    });
}
};
