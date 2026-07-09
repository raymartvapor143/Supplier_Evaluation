<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->string('authorization_letter')->nullable()->after('signature');

        });

        DB::statement("
            ALTER TABLE users
            MODIFY role ENUM(
                'end_user',
                'administrator',
                'pgso',
                'head',
                'presentative_staff'
            ) DEFAULT 'end_user'
        ");
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn('authorization_letter');

        });

        DB::statement("
            ALTER TABLE users
            MODIFY role ENUM(
                'end_user',
                'administrator',
                'pgso',
                'head'
            ) DEFAULT 'end_user'
        ");
    }
};
