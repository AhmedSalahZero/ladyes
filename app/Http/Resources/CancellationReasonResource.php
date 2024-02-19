<?php

namespace App\Http\Resources;

use App\Models\CancellationReason;
use Illuminate\Http\Resources\Json\JsonResource;

class CancellationReasonResource extends JsonResource
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
		 * @var CancellationReason $this
		 */
        return [
			'name'=>$this->getName(getApiLang()),
		];
    }
}
