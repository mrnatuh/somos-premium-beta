<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'id' => 2,
            'name' => 'Marcio Renan',
            'email' => 'mrnatuh@live.com',
            'access' => 'admin',
        ]);

        \App\Models\User::factory()->create([
            'id' => 1,
            'name' => 'Cleiton Rosa',
            'email' => 'cleitonrosa@gmail.com',
            'access' => 'admin',
        ]);
    }
}
