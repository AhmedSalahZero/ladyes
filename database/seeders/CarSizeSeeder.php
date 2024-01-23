<?php

namespace Database\Seeders;

use App\Models\CarSize;
use Illuminate\Database\Seeder;

class CarSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CarSize::factory()->create([
			'name_en'=>'vip',
			'name_ar'=>'متميز',
		]);
		
		CarSize::factory()->create([
			'name_en'=>'family',
			'name_ar'=>'عائلي',
		]);
		
		CarSize::factory()->create([
			'name_en'=>'private',
			'name_ar'=>'خاص',
		]);
    }
}
