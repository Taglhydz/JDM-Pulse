<?php

namespace Database\Factories;

use App\Models\Own;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Own>
 */
class OwnFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Own::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'car_id' => $this->faker->numberBetween(1, 5),
            'user_id' => $this->faker->numberBetween(1, 6),
        ];
    }
}
