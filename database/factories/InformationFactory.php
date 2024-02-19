<?php

namespace Database\Factories;

use App\Enum\InformationSection;
use Illuminate\Database\Eloquent\Factories\Factory;

class InformationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
				'section_name'=>InformationSection::CLIENT_PROFILE,
				'name_en'=>$this->faker->name ,
				'name_ar'=>$this->faker->name ,
				'description_en'=>$this->faker->sentences(100) ,
				'description_ar'=>$this->faker->sentences(100) ,
				'is_active'=>true 
        ];
    }
}
