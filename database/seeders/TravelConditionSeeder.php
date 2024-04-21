<?php

namespace Database\Seeders;

use App\Models\TravelCondition;
use Illuminate\Database\Seeder;

class TravelConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		TravelCondition::factory()->create([
			'name_en'=>'Child Seats',
			'name_ar'=>'مقعد للاطفال',
			// 'model_type'=>'Driver'
		]);
		
		TravelCondition::factory()->create([
			'name_en'=>'Allowing Smoking',
			'name_ar'=>'السماح بالتدخين',
			// 'model_type'=>'Driver'
		]);
		
		// TravelCondition::factory()->create([
		// 	'name_en'=>'Allowing Smoking',
		// 	'name_ar'=>'السماح بالتدخين',
		// 	'model_type'=>'Client'
		// ]);
		
    }
}
