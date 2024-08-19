<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CancellationReasonFactory extends Factory
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
				'model_type'=>[
					'Driver',
					'Client'
				][$this->faker->numberBetween(0,1)] ,
				'name_ar'=>$this->faker->name ,
				'is_active'=>true 
        ];
    }
}
