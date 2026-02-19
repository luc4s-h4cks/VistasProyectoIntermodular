<?php

namespace Database\Seeders;

use App\Models\Usuario;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Usuario::factory(10)->create();

        Usuario::factory()->create([
            'nombre' => 'Test',
            'apellidos' => 'User',
            'nombre_usuario' => 'testuser',
            'email' => 'test@example.com',
        ]);
    }
}
