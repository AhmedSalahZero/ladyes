<?php

namespace Database\Factories;

use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class DriverFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
				'first_name'=>$this->faker->name ,
				'last_name'=>$this->faker->name ,
				'country_id'=>$countryId = Country::inRandomOrder()->first()->id  ,
				'city_id'=>$cityId = City::factory()->create(['country_id'=>$countryId])->id  ,
				'email'=>$this->faker->email ,
				'phone'=>$this->faker->phoneNumber() ,
				'is_verified'=>$this->faker->boolean(),
				'is_listing_to_orders_now'=>0 ,
				'verification_code'=>$this->faker->name ,
				'plate_letters'=>$this->faker->numberBetween(1000,9000),
				'car_color'=>$this->faker->colorName,
				'car_max_capacity'=>$this->faker->numberBetween(1,5),
				'car_id_number'=>$this->faker->numberBetween(99999,99999999),
				'has_traffic_tickets'=>0,
				'manufacturing_year'=>$this->faker->year ,
				'size_id'=>2 ,
				'make_id'=>$modelId = CarMake::factory()->create()->id ,
				'model_id'=>CarModel::factory()->create(['make_id'=>$modelId])->id ,
				
        ];
    }
}
