<?php

namespace Database\Seeders;

use App\Models\Information;
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
		$this->call(InformationSeeder::class);
		$this->call(HelpSeeder::class);
		$this->call(PromotionSeeder::class);
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
		$this->call(ClientSeeder::class);
		$this->call(EmergencyContactsSeeder::class);
		$this->call(CouponSeeder::class);
    }
}
