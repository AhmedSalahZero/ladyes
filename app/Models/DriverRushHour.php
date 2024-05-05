<?php

namespace App\Models;

use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasCreatedAt;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
	 ** هنا هنحتفظ بسجل الاوقات اللي اشتغل فيها في اوقات الذروة 
	 * * علشان لو اشتغل شهر كامل في وقت الذروة يوميا بياخد وسام
	 */
	
class DriverRushHour extends Model 
{
    use  IsBaseModel, HasFactory ,HasCreatedAt;
	protected $guarded =[
		'id'
	];
	public function driver()
	{
		return $this->belongsTo(Driver::class,'driver_id','id');
	}
}
