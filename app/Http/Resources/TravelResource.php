<?php

namespace App\Http\Resources;

use App\Models\Travel;
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
			'price' => $mainPriceWithoutDiscountAndTaxesAndCashFees = $this->hasStarted() ?  $this->calculateClientActualPriceWithoutDiscount() : 0,
			'total_fines'=>$totalFines = $this->client->getTotalAmountOfUnpaid(),
			'promotion_percentage' =>  $this->getPromotionPercentage(),
			'coupon_amount' => $couponAmount = $this->getCouponDiscountAmount(),
			'cash_fees'=>$cashFees = $this->calculateCashFees(),
			'tax_amount'=>$taxAmount = $this->calculateTaxAmount($mainPriceWithoutDiscountAndTaxesAndCashFees,$couponAmount,$cashFees,$promotionAmount = $this->calculatePromotionAmount($mainPriceWithoutDiscountAndTaxesAndCashFees,$couponAmount,$cashFees,$totalFines),$totalFines),
			'total_price' =>   $this->calculateClientTotalActualPrice($mainPriceWithoutDiscountAndTaxesAndCashFees,$couponAmount,$promotionAmount   ,$taxAmount,$cashFees,$totalFines)  ,
		   
		] : [];
        return [
			'id'=>$this->id , 
			'from_address'=>$this->getFromAddress(),
			'to_address'=>$this->getToAddress(),
			'is_secure_code'=>$isSecure=$this->getIsSecure(),
			'secure_code'=>$this->when($isSecure,$this->getSecureCode()),
			'price_details'=>$this->when($this->isCompleted() , $priceDetails ),
			'expected_arrival_time'=>isset($result['duration_in_seconds']) ? __('Estimated Arrival Time :time',['time'=>now()->addSeconds($result['duration_in_seconds'])->format('g:i A')]) : '-' ,
			'expected_arrival_distance'=>isset($result['distance_in_meter']) ? __(':distance Km Away',['distance'=>round($result['distance_in_meter'] / 1000,1) ]) : '-' ,
			'client' => $client->getResource(),
			'driver'=>$driver->getResource(),
		];
    }
}
