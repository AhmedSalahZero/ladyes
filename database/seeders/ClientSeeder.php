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
		$client = Client::factory()->create();
		$address = Address::factory()->make()->toArray();
		$client->addresses()->create($address);
		
    }
}
