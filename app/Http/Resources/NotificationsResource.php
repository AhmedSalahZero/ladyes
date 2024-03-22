<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationsResource extends JsonResource
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
			'title'=>$this->data['title_'.getApiLang()],
			'message'=>$this->data['message_'.getApiLang()],
			'type'=>$this->data['type'],
			'created_at'=>$this->data['createdAtFormatted']
		];
    }

}
