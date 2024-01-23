<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class AreaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
		$country = Country::inRandomOrder()->first() ;
        return [
            'name_en'=>$this->faker->name,
            'name_ar'=>$this->faker->name,
            'city_id'=>City::factory()->create([
				'country_id'=>$country->id
			])->id ,
			'latitude'=>$this->faker->latitude() ,
			'longitude'=>$this->faker->longitude()
        ];
    }
}
