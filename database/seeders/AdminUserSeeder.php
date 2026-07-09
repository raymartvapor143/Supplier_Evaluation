<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Office;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Get first office (or create one if none exists)
        $office = Office::first();

        if (!$office) {
            $office = Office::create([
                'name' => 'NA'
            ]);
        }

        User::updateOrCreate(
            [
                'email' => 'admin@davsur2026.com'
            ],
            [
                'name' => 'System Administrator',
                'office_id' => $office->id,
                'role' => 'administrator',
                'status' => 'active',
                'password' => Hash::make('++admin@2026.davsur++'),
                'signature' => null
            ]
        );
    }
}
