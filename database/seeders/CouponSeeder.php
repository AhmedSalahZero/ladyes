<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;
use PHPUnit\Framework\Constraint\Count;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		Coupon::factory()->create([
			'code'=>'abc123',
			'number_of_uses'=>4
		]);
		
		Coupon::factory()->count(10)->create();
    }
}
