<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
				'name_en'=>$this->faker->name ,
				'name_ar'=>$this->faker->name ,
				'description_en'=>$this->faker->address ,
				'description_ar'=>$this->faker->address ,
				'latitude'=>$this->faker->latitude(),
				'longitude'=>$this->faker->longitude()
        ];
    }
}
