<?php

namespace App\Http\Resources;

use App\Models\Coupon;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponsResource extends JsonResource
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
		 * @var Coupon $this
		 */
        return [
			'name'=>$this->getName(getApiLang()),
			'start_date'=>$this->getStartDateFormatted(),
			'end_date'=>$this->getEndDateFormatted(),
			'discount_type'=>$this->getDiscountTypeFormatted(),
			'discount_amount'=>$this->getDiscountAmountFormatted(),
		];
    }
}
