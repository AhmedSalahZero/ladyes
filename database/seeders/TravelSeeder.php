<?php

namespace Database\Seeders;

use App\Enum\PaymentType;
use App\Enum\TransactionType;
use App\Enum\TravelStatus;
use App\Helpers\HHelpers;
use App\Models\CancellationReason;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\Travel;
use Illuminate\Database\Seeder;

class TravelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$travel = Travel::factory()->create([
			'status'=>TravelStatus::CANCELLED,
			'cancelled_by'=>'Client',
			'cancellation_reason_id'=>CancellationReason::where('model_type','Client')->inRandomOrder()->first()->id	
		]);
		
		
		
		Travel::factory()->create([
			'status'=>TravelStatus::ON_THE_WAY
		]);
		
        Travel::factory()->create()->each(function(Travel $travel){
			/**
			 * @var Payment $payment 
			 */
			$payment = Payment::factory()->create([
			
				'type'=> PaymentType::CASH,
				'cash_fees'=>1 ,
				'application_share'=>12,
				'driver_share'=>23,
				'car_size_price'=>7,
				'travel_id'=>$travel->id ,
				'model_id'=>$travel->client->id ,
				'model_type'=>HHelpers::getClassNameWithoutNameSpace($travel->client)
			]);
			Transaction::factory()->create([
				'amount'=>$amount = $payment->getPrice() * -1 ,
				'type'=>TransactionType::PAYMENT,
				'type_id'=>$payment->id ,
				'model_id'=>$travel->client->id ,
				'model_type'=>HHelpers::getClassNameWithoutNameSpace($travel->client),
				'note_en'=>__('Amount Has Been Subtracted From Your Wallet :amount :currency For Travel # :travelId',['amount'=>number_format($amount),'currency'=>'SAR','travelId'=>$travel->id],'en'),
				'note_ar'=>__('Amount Has Been Subtracted From Your Wallet :amount :currency For Travel # :travelId',['amount'=>number_format($amount),'currency'=>__('SAR',[],'ar'),'travelId'=>$travel->id],'ar')
			]);
		});
    }
}
