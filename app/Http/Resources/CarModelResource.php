<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CarModelResource extends JsonResource
{
    public function toArray($request)
    {
        return [
			'id'=>$this->id , // used in ajax [helper]
			'name'=>$this->getName(app()->getLocale()), // used in ajax [helper]
		];
    }
}
