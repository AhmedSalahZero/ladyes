<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $city = City::factory()->create([
		'name_en'=>'Mecca',
		'name_ar'=>'مكة',
		'longitude'=>'21.42250',
		'latitude'=>'39.82611',
		'country_id'=>194 // sa
	   ]);
	   

	   
	   $city = City::factory()->create([
		'name_en'=>'Cairo',
		'name_ar'=>'القاهرة',
		'longitude'=>'21.42250',
		'latitude'=>'39.82611',
		'country_id'=>65 // sa
	   ]);
	   

	   
	   
    }
}
