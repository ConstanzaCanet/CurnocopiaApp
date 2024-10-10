<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
        'name' => 'Usuario Regular',
        'email' => 'user@example.com',
        'password' => bcrypt('password'),
        'role' => 'user', 
        ]);

        User::factory()->admin()->create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
    }
}
