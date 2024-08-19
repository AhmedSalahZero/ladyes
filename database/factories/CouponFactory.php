<?php

namespace Database\Factories;

use App\Enum\DiscountType;
use App\Helpers\HStr;
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
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
				'name_ar'=>$this->faker->name ,
				'number_of_uses'=>$this->faker->numberBetween(1,3) ,
				'code'=>HStr::generateUniqueCodeForModel('Coupon','code'),
				'start_date'=>now(),
				'end_date'=>now()->addMonth(),
				'discount_type'=>DiscountType::FIXED,
				'discount_amount'=>$this->faker->numberBetween(10,100),
        ];
    }
}
