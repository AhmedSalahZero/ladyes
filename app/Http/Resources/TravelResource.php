<?php

namespace App\Http\Resources;

use App\Services\DistanceMatrix\GoogleDistanceMatrixService;
use Illuminate\Http\Resources\Json\JsonResource;

class TravelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
		$googleDistanceMatrixService = new GoogleDistanceMatrixService();
		$driver = $this->driver;
		$client = $this->client ;
		$result = $driver && $driver->getLongitude() ? $googleDistanceMatrixService->getExpectedArrivalTimeBetweenTwoPoints($driver->getLatitude(),$driver->getLongitude(),$this->getFromLatitude(),$this->getFromLongitude()) : [];
		
		/**
		 * @var Travel $this 
		 */
		$priceDetails = $this->isCompleted() ? [
			'price' => $mainPriceWithoutDiscountAndTaxesAndCashFees = $this->started_at ?  $this->calculateClientActualPriceWithoutDiscount() : 0,
			'total_fines'=>$fines = $this->client->getTotalAmountOfUnpaid(),
			'coupon_amount' => $couponAmount = $this->getCouponDiscountAmount(),
			'tax_amount'=>$taxAmount = $this->calculateTaxAmount($mainPriceWithoutDiscountAndTaxesAndCashFees),
			'cash_fees'=>$cashFees = $this->calculateCashFees(),
			/**
			 * * لاحظ احنا هنا ضفنا الغرمات علشان دا اللي هيظهر لليوزر .. اما اثناء الدفع بنقسم علي مرحلتين ..يعني كل وحده هيكون ليها الدفع بتاعها
			 */
			'total_price' => $fines +  $this->calculateClientTotalActualPrice($couponAmount,$taxAmount,$cashFees)  ,
		   
		] : [];
        return [
			'id'=>$this->id , 
			'from_address'=>$this->getFromAddress(),
			'to_address'=>$this->getToAddress(),
			'is_secure_code'=>$isSecure=$this->getIsSecure(),
			'price_details'=>$this->when($this->isCompleted() , $priceDetails ),
			'secure_code'=>$this->when($isSecure,$this->getSecureCode()),
			'expected_arrival_time'=>isset($result['duration_in_seconds']) ? __('Estimated Arrival Time :time',['time'=>now()->addSeconds($result['duration_in_seconds'])->format('g:i A')]) : '-' ,
			'expected_arrival_distance'=>isset($result['distance_in_meter']) ? __(':distance Km Away',['distance'=>round($result['distance_in_meter'] / 1000,1) ]) : '-' ,
			'client' => $client->getResource(),
			'driver'=>$driver->getResource(),
		];
    }
}