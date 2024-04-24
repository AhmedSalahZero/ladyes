<?php

namespace App\Http\Resources;

use App\Models\Slider;
use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
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
		 * @var Slider $this
		 */
        return [
			'id'=>$this->id ,
			'image'=>$this->getFullImagePath($this->image),
			'created_at'=>$this->getCreatedAt()
		];
    }
}
