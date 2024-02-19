<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
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
				'country_id'=> Country::inRandomOrder()->first()->id  ,
				'email'=>$this->faker->email ,
				'phone'=>$this->faker->phoneNumber() ,
				'is_verified'=>$this->faker->boolean(),
				'can_pay_by_cash'=>1 ,
				'verification_code'=>random_int(1000, 9999)
        ];
    }
}
