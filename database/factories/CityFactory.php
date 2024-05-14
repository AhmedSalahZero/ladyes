<?php

namespace Database\Factories;

use App\Models\Country;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Database\Eloquent\Factories\Factory;

class CityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
		$location = $this->faker->localCoordinates();
        return [
            'name_en'=>$this->faker->city,
            'name_ar'=>$this->faker->city,
            // 'price'=>$this->faker->numberBetween(100,200),
            'km_price'=>$this->faker->numberBetween(2,3),
            'minute_price'=>$this->faker->numberBetween(2,3),
            'operating_fees'=>$this->faker->numberBetween(10,20),
			'location'=>new Point($location['latitude'],$location['longitude']),
            'country_id'=>Country::inRandomOrder()->first()->id,
			// 'latitude'=>$this->faker->latitude() ,
			// 'longitude'=>$this->faker->longitude()
        ];
    }
}
