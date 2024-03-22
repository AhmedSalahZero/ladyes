<?php

namespace Database\Factories;

use App\Enum\TransactionType;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'amount'=>$this->faker->numberBetween(1000,1500),
			'type'=>TransactionType::PAYMENT,
			'type_id'=>0,
			'model_type'=>null
        ];
    }
}
