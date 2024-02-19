<?php

namespace App\Http\Resources;

use App\Models\CarMake;
use Illuminate\Http\Resources\Json\JsonResource;

class CarMakeResource extends JsonResource
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
		 * @var CarMake $this
		 */
        return [
			'name'=>$this->getName(getApiLang()),
			'image'=>$this->image ? asset($this->image) : null
		];
    }
}
