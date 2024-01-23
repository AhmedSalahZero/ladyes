<?php 
namespace App\Traits\Models;


trait HasInvitationCode 
{
	public function attachInvitationCode($senderId , $code)
	{
		$exists = $this->receivedInvitationCodes()->where('sender_id',$senderId)->exists();
		if($exists){
			return [
				'status'=>false ,
				'message'=>__('Invitation Code Already Exist'),
				'data'=>[],
			];
		}
		$this->receivedInvitationCodes()->attach($senderId , [
			'code'=>$code ,
			'created_at'=>now()
		]);
		return [
			'status'=>true ,
			'message'=>__('Invitation Code Has Been Added Successfully'),
			'data'=>[]
		];
		
	}
}
