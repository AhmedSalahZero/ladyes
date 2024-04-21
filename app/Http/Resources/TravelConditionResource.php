<?php

namespace App\Http\Resources;

use App\Models\CarSize;
use App\Models\TravelCondition;
use Illuminate\Http\Resources\Json\JsonResource;

class TravelConditionResource extends JsonResource
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
		 * @var TravelCondition $this
		 */
        return [
			'id'=>$this->id,
			'name'=>$this->getName(getApiLang()),
		];
    }
}
