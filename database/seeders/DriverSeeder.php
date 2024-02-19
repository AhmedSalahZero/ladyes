<?php

namespace Database\Seeders;

use App\Models\Driver;
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
		 Driver::factory()->create([
			'first_name'=>'ahmed',
			'last_name'=>'salah',
			'email'=>'asalahdev5@gmail.com',
			'phone'=>'01025894984',
			'country_id'=>65,
			'city_id'=>2
		]);
    }
}
