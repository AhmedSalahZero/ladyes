<?php

namespace Database\Factories;

use App\Enum\DiscountType;
use Illuminate\Database\Eloquent\Factories\Factory;

class PromotionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
		// $discountTypes = array_keys(DiscountType::all());
        return [
				'name_en'=>$this->faker->name ,
				'name_ar'=>$this->faker->name ,
				'start_date'=>now(),
				'end_date'=>now()->addMonth(),
				'discount_type'=>DiscountType::PERCENTAGE,
				// 'discount_type'=>$discountTypes[$this->faker->numberBetween(0,count($discountTypes)-1)],
				'discount_amount'=>$this->faker->numberBetween(10,20),
        ];
    }
}
