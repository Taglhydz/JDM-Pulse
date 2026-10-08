<?php

namespace Database\Factories;

use App\Models\Motorization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Motorization>
 */
class MotorizationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Motorization::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'power' => $this->faker->numberBetween(50, 500),
            'torque' => $this->faker->numberBetween(50, 500),
            'consumption' => $this->faker->randomFloat(1, 5, 20),
            'engine_id' => $this->faker->numberBetween(1, 5),
        ];
    }
}
