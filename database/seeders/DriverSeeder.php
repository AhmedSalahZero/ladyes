<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\City;
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
		Driver::factory()->create();
    }
}
