<?php

namespace Database\Factories;

use App\Models\Engine;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Engine>
 */
class EngineFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Engine::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'engine_name' => $this->faker->word,
            'architecture' => $this->faker->word,
            'volume' => $this->faker->numberBetween(1000, 5000),
            'induction' => $this->faker->word,
            'fuel_type' => $this->faker->randomElement(['Gasoline', 'Diesel', 'Electric']),
        ];
    }
}
