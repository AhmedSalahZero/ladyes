<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EmergencyContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
				'name'=>$this->faker->name ,
				'phone'=>$this->faker->phoneNumber() ,
				'country_id'=>65,
				'email'=>$this->faker->email ,
		
        ];
    }
}
