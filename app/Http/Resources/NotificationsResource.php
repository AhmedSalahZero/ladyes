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
			'main_type'=>$this->data['main_type'],
			'secondary_type'=>$this->data['secondary_type'],
			'read_at'=>$this->read_at,
			'created_at'=>$this->data['createdAtFormatted']
		];
    }

}
