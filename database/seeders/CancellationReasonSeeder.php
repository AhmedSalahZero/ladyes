<?php

namespace Database\Seeders;

use App\Models\CancellationReason;
use Illuminate\Database\Seeder;

class CancellationReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		CancellationReason::factory()->create([
			'name_en'=>'Client did not come',
			'name_ar'=>'العميل لم ياتي',
			'model_type'=>'Driver',
		]);
		
		
		CancellationReason::factory()->create([
			'name_en'=>'Driver did not come',
			'name_ar'=>'السائق لم يصل',
			'model_type'=>'Client',
		]);
		
		
    }
}
