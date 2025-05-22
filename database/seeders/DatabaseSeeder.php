<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Admin User',
        //     'email' => 'admin@example.com',
        //     'password' => '12345678',
        //     'role' => 'admin',
        // ]);

        User::firstOrCreate([
            'email' => 'admin@email.com',
        ], [
            'name' => 'Admin',
            'password' => '12345678',
            'role' => 'admin',
        ]);
    }
}
