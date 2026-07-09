<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('evaluation_criteria', function (Blueprint $table) {
        $table->id();
        $table->string('criteria_name');
        $table->timestamps();
    });

    // Insert default data
    DB::table('evaluation_criteria')->insert([
        ['criteria_name' => 'PRICE'],
        ['criteria_name' => 'QUALITY/SERVICE LEVEL'],
        ['criteria_name' => 'CUSTOMER CARE/AFTER SALES SERVICE'],
        ['criteria_name' => 'DELIVERY FULFILLMENT'],
    ]);
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_criteria');
    }
};
