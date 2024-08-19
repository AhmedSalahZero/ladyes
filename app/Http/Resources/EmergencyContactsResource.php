<?php

namespace App\Http\Resources;

use App\Models\EmergencyContact;
use Illuminate\Http\Resources\Json\JsonResource;

class EmergencyContactsResource extends JsonResource
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
		 * @var EmergencyContact $this
		 */

        return [
			'id'=>$this->id ,
			'name'=>$this->getName() ,
			'email'=>$this->getEmail(),
			'phone'=>$this->getPhone(),
			'can_receive_travel_info'=>$this->canReceiveTravelInfo(),
			'country'=>$this->country ? new CountryResource($this->country) : null
		];
    }
}
