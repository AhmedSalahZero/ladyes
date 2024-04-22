<?php

namespace App\Models;

use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasCreatedAt;
use App\Traits\Models\HasModelType;
use App\Traits\Scope\HasDefaultOrderScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * * عباره عن رسالة الدعم (تذكرة الدعم) وهي عباره عن شكاوي واقترحات المستخدمين
 */
class SupportTicket extends Model
{
    use IsBaseModel ;
    use HasDefaultOrderScope ;
    use HasModelType;
    use HasFactory ;
	use HasCreatedAt;
	public function driver()
	{
		return $this->belongsTo(Driver::class , 'model_id','id');
	}
	public function client()
	{
		return $this->belongsTo(Client::class , 'model_id','id');
	}
	
	public function getSubject():string 
	{
		return $this->subject;
	}
	public function getMessage():string 
	{
		return $this->message ;
	}
	protected $guarded = [
		'id'
	];
	
}
