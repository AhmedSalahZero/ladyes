<?php

namespace Database\Factories;

use App\Models\CarMake;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;
use PHPUnit\Framework\Constraint\Count;

class CityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name_en'=>$this->faker->city,
            'name_ar'=>$this->faker->city,
            'price'=>$this->faker->numberBetween(100,200),
            'rush_hour_price'=>$this->faker->numberBetween(100,200),
            'country_id'=>Country::inRandomOrder()->first()->id,
			// 'latitude'=>$this->faker->latitude() ,
			// 'longitude'=>$this->faker->longitude()
        ];
    }
}