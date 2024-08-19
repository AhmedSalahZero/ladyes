<?php

namespace Database\Seeders;

use App\Models\CarMake;
use Illuminate\Database\Seeder;

class CarMakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CarMake::factory()->create([
			'name_en'=>'toyota',
			'name_ar'=>'تويوتا',
		]);
		
    }
}
