<?php

namespace Database\Factories;

use App\Models\Evento;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Evento>
 */
class EventoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'titulo'      => fake()->sentence(4),
            'descricao'   => fake()->paragraph(),
            'data_evento' => now()->addDays(fake()->numberBetween(1, 30)),
        ];
    }
}
