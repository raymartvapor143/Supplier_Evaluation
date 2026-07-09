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
        $table->string('request_type')
              ->after('requested_to'); // update | delete
    });
}

public function down(): void
{
    Schema::table('requests', function (Blueprint $table) {
        $table->dropColumn('request_type');
    });
}
};
