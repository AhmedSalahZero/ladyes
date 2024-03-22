<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$client = Client::factory()->create([
			'phone'=>'01025894984',
			'country_id'=>65,
			'email'=>'asalahdev5@gmail.com',
			'verification_code'=>1234
		]);
		$address = Address::factory()->make([
			'category'=>'المنزل',
			'description'=>'عنوان المنزل',
		])->toArray();
		$client->addresses()->create($address);
		
    }
}
