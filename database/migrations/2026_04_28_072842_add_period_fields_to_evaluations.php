<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {

            // Prevent duplicate column error
            if (!Schema::hasColumn('evaluations', 'period_year')) {
                $table->year('period_year')->after('covered_period');
            }
        });
    }

    public function down(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {

            // Prevent "column doesn't exist" error
            if (Schema::hasColumn('evaluations', 'period_year')) {
                $table->dropColumn('period_year');
            }
        });
    }
};
