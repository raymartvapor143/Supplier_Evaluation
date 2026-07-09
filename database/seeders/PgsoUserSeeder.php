<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PgsoUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'PGSO User',
            'office_id' => 93,
            'email' => 'pgso.davsur@gmail.com',
            'password' => Hash::make('davsur.2026@pgso'),
            'status' => 'active',
            'role' => 'pgso',
            'image' => null,
        ]);
    }
}
