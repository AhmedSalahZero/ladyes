<?php

namespace App\Http\Resources;

use App\Models\Country;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
		/**
		 * @var Country $this 
		 */
		
		
        return [
			'id'=>$this->getId(),
			'name'=>$this->getName(getApiLang()),
			'code'=>$this->getPhoneCode(),
			'iso2'=>$this->getIso2(),
			'iso3'=>$this->getIso3(),
			'latitude'=>$this->getLatitude(),
			'longitude'=>$this->getLongitude(),
		];
    }
}
