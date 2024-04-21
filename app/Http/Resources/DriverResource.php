<?php

namespace App\Http\Resources;

use App\Models\Driver;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
		/**
		 * @var Driver $this 
		 */
		$country = $this->getCountry() ;
        return [
			'id'=>$this->id ,
			'access_token'=>$this->getCurrentToken(),
			'name'=>$this->getFullName(),
			'email'=>$this->getEmail(),
			'phone'=>$this->getPhone(),
			'image'=>$this->getFirstMedia('image') ? $this->getFirstMedia('image')->getFullUrl() : getDefaultImage() ,
			'current_balance'=>$this->getTotalWalletBalance(),
			'rates'=>$this->getRatesForApi(),
			'country'=>$country ? new CountryResource($country) : null,
			'city'=> $this->city ? new CityResource($this->city) : null,
			'is_verified'=>$this->getIsVerified(),
			'is_listing_to_orders_now'=>$this->getIsListingToOrders(),
			'can_receive_orders'=>$this->getCanReceiveOrders(),
			'verification_code'=>$this->getVerificationCode(),
			'birth_date'=>$this->getBirthDate(),
			'id_number'=>$this->getIdNumber(),
			'deduction_percentage'=>$this->getDeductionPercentage(),
			'driving_range'=>$this->getDrivingRange(),
			'invitation_code'=>$this->getInvitationCode(),
			'avg_rate'=>$this->getAvgRate(),
			'location'=>$this->getLocation(),
			'is_banned'=>$this->isBanned(),
			'distance_in_km'=>$this->when(!is_null($this->distance_in_km) , $this->getDistanceInKm()),
			'completed_travels_percentage'=>$this->getCompletedTravelsPercentageFormatted(),
			'cancelled_travels_percentage'=>$this->getCancelledTravelsPercentageFormatted(),
			'created_at'=>$this->getCreatedAtFormatted(),
			'car'=>[
				'manufacturing_year'=>$this->getManufacturingYear(),
				'plate_letters'=>$this->getPlateLetters(), 
				'plate_numbers'=>$this->getPlateNumbers(),
				'car_color'=>$this->getCarColorName(),
				'car_max_capacity'=>$this->getCarMaxCapacity(),
				'car_id_number'=>$this->getCarIdNumber(),
				'has_traffic_tickets'=>$this->getHasTrafficTickets(),
				'size'=>$this->size ? new CarSizeResource($this->size) : null,
				'make'=>$this->make ? new CarMakeResource($this->make) : null,
				'model'=>$this->model ? new CarModelResource($this->model) : null,
				
			] 
		];
    }
}
