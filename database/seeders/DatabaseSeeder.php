<?php

namespace Database\Seeders;

use App\Enums\UserRolEnum;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (User::count() === 0) {
            User::factory(10)->create();
        }

        if (!User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'nombre' => 'Test',
                'apellido_paterno' => 'User',
                'email' => 'test@example.com',
                'rol' => UserRolEnum::RECEPCIONISTA,
            ]);
        }

        if (!User::where('email', 'admin@example.com')->exists()) {
            User::factory()->create([
                'nombre' => 'Admin',
                'apellido_paterno' => 'User',
                'email' => 'admin@example.com',
                'rol' => UserRolEnum::ADMINISTRADOR,
            ]);
        }

        $this->call([
            CarasDentalesSeeder::class,
            DienteSeeder::class,
            EnfermedadSeeder::class,
        ]);

        $this->call(PacienteCompletoSeeder::class);
    }
}
