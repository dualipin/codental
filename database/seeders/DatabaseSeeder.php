<?php

namespace Database\Seeders;

use App\Models\User;
use App\UserRolEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'nombre' => 'Test',
            'apellido_paterno' => 'User',
            'email' => 'test@example.com',
            'rol' => UserRolEnum::RECEPCIONISTA,
        ]);

        User::factory()->create([
            'nombre' => 'Admin',
            'apellido_paterno' => 'User',
            'email' => 'admin@example.com',
            'rol' => UserRolEnum::ADMINISTRADOR,
        ]);
    }
}
