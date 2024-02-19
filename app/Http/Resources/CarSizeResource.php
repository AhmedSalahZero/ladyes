<?php

namespace App\Http\Resources;

use App\Models\CarSize;
use Illuminate\Http\Resources\Json\JsonResource;

class CarSizeResource extends JsonResource
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
		 * @var CarSize $this
		 */
        return [
			'name'=>$this->getName(getApiLang()),
			'image'=>asset($this->image)
		];
    }
}
