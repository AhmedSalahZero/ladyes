<?php

namespace Database\Seeders;

use App\Enum\CancellationReasonPhases;
use App\Enum\PaymentType;
use App\Enum\TransactionType;
use App\Enum\TravelStatus;
use App\Helpers\HHelpers;
use App\Models\Address;
use App\Models\Admin;
use App\Models\CancellationReason;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\CarSize;
use App\Models\City;
use App\Models\Client;
use App\Models\Coupon;
use App\Models\Driver;
use App\Models\EmergencyContact;
use App\Models\Payment;
use App\Models\SupportTicket;
use App\Models\Transaction;
use App\Models\Travel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CancellationReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		ini_set('memory_limit', -1);
		ini_set('max_execution_time', 0);

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
