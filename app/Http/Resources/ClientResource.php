<?php

namespace App\Http\Resources;

use App\Models\Client;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
		 * @var Client $this 
		 */
		$country = $this->getCountry() ; 
		// $location = $this->getLocation();
		
        return [
			'id'=>$this->id ,
			'name'=>$this->getFullName(),
			'email'=>$this->getEmail(),
			'phone'=>$this->getPhone(),
			'current_balance'=>$this->getTotalWalletBalance(),
			'country'=>$country ? new CountryResource($country) : null,
			'is_verified'=>$this->getIsVerified(),
			'can_pay_by_cash'=>$this->getCanPayByCash(),
			'verification_code'=>$this->getVerificationCode(),
			'birth_date'=>$this->getBirthDateFormatted(),
			'avg_rate'=>$this->getAvgRate(),
			'location'=>$this->getLocation(),
			'is_banned'=>$this->isBanned(),
			'created_at'=>$this->getCreatedAtFormatted(),
			'addresses'=>[]
		];
    }
}
