<?php

namespace App\Http\Resources;

use App\Models\Transaction;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionsResource extends JsonResource
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
		 * @var Transaction $this 
		 */
		
        return [
			'id'=>$this->id,
			'type'=>$this->getType(),
			'amount'=>$this->getAmount(),
			'note'=>$this->getNote()
		];
    }

}
