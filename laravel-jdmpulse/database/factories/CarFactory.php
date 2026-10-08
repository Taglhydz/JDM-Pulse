<?php

namespace Database\Factories;

use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Car::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'brand' => $this->faker->company,
            'model' => $this->faker->word,
            'year' => $this->faker->year,
            'color' => $this->faker->safeColorName,
            'generation' => $this->faker->word,
            'image_url' => $this->faker->imageUrl,
			'edition_id' => $this->faker->numberBetween(1, 5),
        ];
    }
}
