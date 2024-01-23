<?php

namespace Database\Factories;

use App\Models\CarMake;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarModelFactory extends Factory
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
            // 'manufacturing_year'=>$this->faker->year,
			'make_id'=>CarMake::factory()->create()->id  ,
            'image'=>$this->faker->image('public/storage/CarModel',400,300, null, false),
        ];
    }
}
