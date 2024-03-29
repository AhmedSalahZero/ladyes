<?php

namespace Database\Seeders;

use App\Models\City;
use Carbon\Carbon;
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
		// 'longitude'=>'21.42250',
		// 'latitude'=>'39.82611',
		'country_id'=>194 // sa
	   ]);
	   
	   $city->rushHours()->create([
		'start_time'=>now()->toTimeString(),
		'end_time'=>now()->addHour()->toTimeString(),
		'percentage'=>'1/5',
		'km_price'=>$city->km_price + 10 ,
		'minute_price'=>$city->minute_price + 10 ,
		'operating_fees'=>$city->operating_fees + 1 ,
	   ]);
	   

	   
	   $city = City::factory()->create([
		'name_en'=>'Cairo',
		'name_ar'=>'القاهرة',

		// 'longitude'=>'21.42250',
		// 'latitude'=>'39.82611',
		'country_id'=>65 // eg
	   ]);
	   
	   $city->rushHours()->create([
		'start_time'=>now()->toTimeString(),
		'end_time'=>now()->addHour()->toTimeString(),
		// 'price'=>$city->price + 10 ,
		'percentage'=>'2/5',
		'km_price'=>$city->km_price + 10 ,
		'minute_price'=>$city->minute_price + 10 ,
		'operating_fees'=>$city->operating_fees + 1 ,
	   ]);
	   

	   
	   
    }
}
