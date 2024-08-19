<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupportTicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'subject'=>$this->faker->sentence(6),
            'message'=>$this->faker->sentence(100),
			'model_id'=>Client::first()->id ,
			'model_type'=>'Client'
        ];
    }
}
