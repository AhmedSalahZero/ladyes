<?php

namespace App\Http\Resources;

use App\Models\Client;
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
        return [
			'id'=>$this->id ,
			'name'=>$this->getFullName(),
			'email'=>$this->getEmail(),
			'phone'=>$this->getPhone(),
			'country'=>$country ? new CountryResource($country) : null,
			'is_verified'=>$this->getIsVerified(),
			'can_pay_by_cash'=>$this->getCanPayByCash(),
			'verification_code'=>$this->getVerificationCode(),
			'birth_date'=>$this->getBirthDateFormatted(),
			'is_banned'=>$this->isBanned(),
			'created_at'=>$this->getCreatedAtFormatted(),
			
		];
    }
}
