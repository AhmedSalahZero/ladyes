<?php

namespace Database\Seeders;

use App\Enum\CancellationReasonPhases;
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
			'name_en'=>'By Mistake',
			'name_ar'=>'لقد طلبت عن طريق الخطا',
			'model_type'=>'Client',
			'phase'=>CancellationReasonPhases::WHILE_SEARCHING
		]);
		
		
		CancellationReason::factory()->create([
			'name_en'=>'Driver Is Too Late',
			'name_ar'=>'السائق تاخر كثيرا',
			'model_type'=>'Client',
			'phase'=>CancellationReasonPhases::BEFORE_DRIVER_ARRIVE
		]);
		
		
		CancellationReason::factory()->create([
			'name_en'=>'Bad Behavior',
			'name_ar'=>' سلوك السائق غير مناسب لي ',
			'model_type'=>'Client',
			'phase'=>CancellationReasonPhases::AFTER_DRIVER_ARRIVE
		]);
		
		
		
		
    }
}
