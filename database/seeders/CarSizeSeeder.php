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
		
		$carSize = CarSize::factory()->create([
			'name_en'=>'Eco',
			'name_ar'=>'Eco',
			'image'=>CarSize::ECO_IMAGE
		]);
		
		$carSize->countryPrices()->attach([
			
			'country_id'=>65
		],[
			'price'=>40,
			'model_type'=>'CarSize'
		]);
		
		
		
        $carSize = CarSize::factory()->create([
			'name_en'=>'vip',
			'name_ar'=>'متميز',
			'image'=>CarSize::VIP_IMAGE
		]);
		$carSize->countryPrices()->attach([
			
			'country_id'=>194
		],[
			'price'=>90,
			'model_type'=>'CarSize'
		]);
		
		$carSize->countryPrices()->attach([
			
			'country_id'=>65
		],[
			'price'=>90,
			'model_type'=>'CarSize'
		]);
		
		$carSize = CarSize::factory()->create([
			'name_en'=>'family',
			'name_ar'=>'عائلي',
			'image'=>CarSize::FAMILY_IMAGE
		]);
		$carSize->countryPrices()->attach([
			
			'country_id'=>194
		],
	['price'=>90,
	'model_type'=>'CarSize']
	);
	
	$carSize->countryPrices()->attach([
			
		'country_id'=>65
	],
['price'=>90,
'model_type'=>'CarSize']
);

		$carSize = CarSize::factory()->create([
			'name_en'=>'private',
			'name_ar'=>'خاص',
			'image'=>CarSize::PRIVATE_IMAGE
		]);
		$carSize->countryPrices()->attach([
		
			'country_id'=>194
		],[
			'price'=>20,
			'model_type'=>'CarSize'
		]);
		$carSize->countryPrices()->attach([
		
			'country_id'=>65
		],[
			'price'=>20,
			'model_type'=>'CarSize'
		]);
    }
}
