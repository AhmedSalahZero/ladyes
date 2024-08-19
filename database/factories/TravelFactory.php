<?php

namespace Database\Factories;

use App\Enum\TravelStatus;
use App\Models\City;
use App\Models\Client;
use App\Models\Coupon;
use App\Models\Driver;
use App\Models\Promotion;
use App\Models\Travel;
use Illuminate\Database\Eloquent\Factories\Factory;

class TravelFactory extends Factory
{
    public function definition()
    {
        return [
            'client_id'=>Client::inRandomOrder()->first()->id ,
			'driver_id'=>Driver::inRandomOrder()->first()->id ,
			'coupon_id'=>Coupon::inRandomOrder()->first()->id ,
			'promotion_id'=>Promotion::inRandomOrder()->first()->id,
			'payment_method'=>'cash',
			'no_km'=>$this->faker->numberBetween(2,15) ,
			'city_id'=>City::where('country_id',194)->first()->id, // saudi arabia
			'status'=>TravelStatus::COMPLETED,
			'from_latitude'=>$this->faker->latitude(),
			'from_longitude'=>$this->faker->longitude(),
			'to_latitude'=>$this->faker->latitude(),
			'to_longitude'=>$this->faker->longitude(),
			'from_address_en'=>$this->faker->address,
			'to_address_en'=>$this->faker->address,
			'from_address_ar'=>$this->faker->address,
			'to_address_ar'=>$this->faker->address,
			
			'started_at'=>now(),
			'ended_at'=>now()->addHour(),
			'is_secure'=>$isSecure = $this->faker->boolean ,
			'secure_code'=>$isSecure ? (new Travel)->generateSecureCode() : null,
        ];
    }
}
