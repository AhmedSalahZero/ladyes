<?php

namespace Database\Factories;

use App\Enum\DeductionType;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\City;
use App\Models\Country;
use Grimzy\LaravelMysqlSpatial\Types\Point;
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
		$location = $this->faker->localCoordinates();
        return [
				'first_name'=>$this->faker->name ,
				'last_name'=>$this->faker->name ,
				'country_id'=>$countryId = Country::inRandomOrder()->first()->id  ,
				'city_id'=>City::factory()->create(['country_id'=>$countryId])->id  ,
				'id_number'=>$this->faker->numberBetween(0000000000,2147483646),
				'email'=>$this->faker->email ,
				'birth_date'=>$this->faker->date(),
				'phone'=>$this->faker->phoneNumber() ,
				'is_verified'=>1,
				'is_listing_to_orders_now'=>1 ,
				// 'verification_code'=>random_int(1000, 9999) ,
				'plate_letters'=>$this->faker->numberBetween(1000,9000),
				'car_color'=>$this->faker->colorName,
				'car_max_capacity'=>$this->faker->numberBetween(1,5),
				'car_id_number'=>$this->faker->numberBetween(99999,99999999),
				'deduction_type'=>DeductionType::PERCENTAGE,
				'deduction_amount'=>5,
				'plate_numbers'=>$this->faker->numberBetween(99999,99999999),
				'has_traffic_tickets'=>0,
				'manufacturing_year'=>$this->faker->year ,
				'size_id'=>2 ,
				'location'=>new Point($location['latitude'],$location['longitude']),
				'make_id'=>$modelId = CarMake::factory()->create()->id ,
				'model_id'=>CarModel::factory()->create(['make_id'=>$modelId])->id ,
				'has_excellent_medal'=>1 ,
				'has_one_year_usage_medal'=>1 ,
				'has_completed_50_travel_medal'=>1,
				'has_rush_hour_medal'=>1 
        ];
    }
}
