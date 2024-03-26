<?php

namespace App\Models;

use App\Traits\Accessors\IsBaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * * الغرمات
 */
class Fine extends Model
{
    use IsBaseModel ;
    use HasFactory ;
	
	protected $guarded = [
		'id'
	];
	public function client()
	{
		return $this->belongsTo(Client::class , 'model_id','id')->where('model_type','Client');
	}
	public function driver()
	{
		return $this->belongsTo(Client::class , 'model_id','id')->where('model_type','Driver');
	}
	public function travel()
	{
		return $this->belongsTo(Travel::class,'travel_id','id');
	}
}
