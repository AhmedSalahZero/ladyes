<?php

namespace App\Http\Resources;

use App\Models\Address;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressesResource extends JsonResource
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
		 * @var Address $this
		 */
        return [
			'id'=>$this->id ,
			'category'=>$this->getCategory(getApiLang()),
			'description'=>$this->getDescription(getApiLang()),
			'latitude'=>$this->getLatitude(),
			'longitude'=>$this->getLongitude(),
		];
    }
}
