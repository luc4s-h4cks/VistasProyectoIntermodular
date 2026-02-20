<?php

namespace Database\Factories;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Usuario>
 */
class UsuarioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Usuario::class;

    public function definition(): array
    {
        return [
            'nombre_usuario' => $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'pass' => Hash::make('12345678'),
            'tipo' => $this->faker->randomElement([
                Usuario::USUARIO,
                Usuario::ADMIN,
                Usuario::MECANICO
            ]),
            'nombre' => $this->faker->firstName(),
            'apellidos' => $this->faker->lastName(),
            'telefono' => $this->faker->numerify('6########'),
            'fecha_nacimiento' => $this->faker->date(),
            'fecha_creacion_cuenta' => now(),
            'img_perfil' => null,
        ];
    }
    public function definition(): array
    {
        return [
            //
        ];
    }
}
