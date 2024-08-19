<?php

namespace App\Http\Resources;

use App\Models\SupportTicket;
use Illuminate\Http\Resources\Json\JsonResource;

class SupportTicketsResource extends JsonResource
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
		 * @var SupportTicket $this
		 */
        return [
			'id'=>$this->id ,
			'subject'=>$this->getSubject() ,
			'message'=>$this->getMessage() ,
			'created_at'=>$this->getCreatedAt()
		];
    }
}
