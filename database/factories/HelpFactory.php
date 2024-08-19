<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class HelpFactory extends Factory
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
				'description_en'=>$this->faker->sentences(100) ,
				'description_ar'=>$this->faker->sentences(100) ,
				'is_active'=>true 
        ];
    }
}
