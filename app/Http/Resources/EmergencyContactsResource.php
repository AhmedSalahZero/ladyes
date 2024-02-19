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
			'country_name'=>$this->getCountryName(getApiLang())
		];
    }
}
