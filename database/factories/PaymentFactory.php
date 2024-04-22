<?php

namespace Database\Factories;

use App\Enum\PaymentStatus;
use App\Enum\PaymentType;
use App\Models\Travel;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'status'=>PaymentStatus::SUCCESS,
			'type'=>PaymentType::CASH,
			'coupon_amount'=>0,
			'price'=>$price=$this->faker->numberBetween(1000,1500),
			'total_price'=>$price,
			'travel_id'=>Travel::inRandomOrder()->first()->id ,
			'currency_name'=>'SAR',
			'model_id'=>null,
			'model_type'=>null 
        ];
    }
}
