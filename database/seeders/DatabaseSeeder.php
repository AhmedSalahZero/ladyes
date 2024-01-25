<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
		$this->call(TravelConditionSeeder::class);
		$this->call(CancellationReasonSeeder::class);
		$this->call(RoleSeeder::class);
		$this->call(AdminSeeder::class);
		$this->call(CarSizeSeeder::class);
		$this->call(CarMakeSeeder::class);
		$this->call(CarModelSeeder::class);
		$this->call(CountrySeeder::class);
		$this->call(CitySeeder::class);
		$this->call(DriverSeeder::class);
		$this->call(EmergencyContactsSeeder::class);
    }
}
