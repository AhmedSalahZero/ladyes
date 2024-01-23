<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CarMakeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name_en'=>$this->faker->name,
            'name_ar'=>$this->faker->name,
            'image'=>$this->faker->image('public/storage/CarMake',400,300, null, false),
        ];
    }
}
