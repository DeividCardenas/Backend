<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Indicador>
 */
class IndicadorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->words(3, true),
            'descripcion' => fake()->sentence(),
            'formula' => 'A/B * 100',
            'meta' => (string) fake()->numberBetween(80, 100),
            'unidad' => '%',
            'responsable_id' => User::factory(),
            'id_norma' => null, // Nullable in migration, preventing FK violation since we don't seed Normas yet
            'activo' => true,
        ];
    }
}
