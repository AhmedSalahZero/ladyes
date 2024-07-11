<?php

namespace Database\Seeders;

use App\Models\Driver;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		 $driver = Driver::factory()->create([
			'first_name'=>'ahmed',
			'last_name'=>'salah',
			'email'=>'asalahdev5@gmail.com',
			'phone'=>'01025894984',
			'country_id'=>65,
			'city_id'=>2,
			'location'=>new Point('30.096954726572758','31.29367752428248')
			// 'verification_code'=>1234
		]);
		$driver->deviceTokens()->create([
			'model_type'=>'Driver',
			'device_name'=>'honor 8x',
			'device_token'=>'eBvHDpiWkGAZx8x-Hv2HX2:APA91bFvKR7cMP-CVznPNpwnFOJUl7IFSSpStj0j9q1wGQalx6oH-qop7hh3jsriJMKfhusWet7cuWIrOsx6U0I6Ade7edM6lt4Vs5zDlejIxhE1ABJAlCvc9Cgq3xemudrKbr8GFyfL'
		]);
		
		Driver::factory()->count(50)->create();
    }
}
