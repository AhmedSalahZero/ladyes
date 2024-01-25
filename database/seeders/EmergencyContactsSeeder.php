<?php

namespace Database\Seeders;

use App\Models\EmergencyContact;
use Illuminate\Database\Seeder;

class EmergencyContactsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		EmergencyContact::factory()->create([
			'name'=>'احمد صلاح',
			'email'=>'asalahdev5@gmail.com',
			'phone'=>'01025894984',
			'country_id'=>65,
		]);
    }
}
