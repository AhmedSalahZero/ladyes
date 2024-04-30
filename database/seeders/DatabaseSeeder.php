<?php

namespace Database\Seeders;

use App\Models\Country;
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
		ini_set('memory_limit', -1);
		ini_set('max_execution_time', 0);
		
		$this->call(CountrySeeder::class);
		$this->call(CarSizeSeeder::class);
		$this->call(CarMakeSeeder::class);
		$this->call(CarModelSeeder::class);
		$this->call(CitySeeder::class);
		$this->call(DriverSeeder::class);
		$this->call(SliderSeeder::class);
		$this->call(InformationSeeder::class);
		$this->call(HelpSeeder::class);
		$this->call(PromotionSeeder::class);
		$this->call(TravelConditionSeeder::class);
		$this->call(RoleSeeder::class);
		$this->call(AdminSeeder::class);
		$this->call(ClientSeeder::class);
		$this->call(CancellationReasonSeeder::class);
		$this->call(EmergencyContactsSeeder::class);
		$this->call(CouponSeeder::class);
		$this->call(TravelSeeder::class);
		$this->call(SupportTicketSeeder::class);
		
		Country::whereIn('id',[194,65])->update([
			'cancellation_fees_for_client'=>10,
			'cancellation_fees_for_driver'=>20,
			'first_travel_bonus'=>5,
		]);
		
    }
}
