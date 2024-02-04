<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Travel extends Model
{
    use HasFactory;
	
	protected $table = 'travels';
	
	public function client():?BelongsTo
	{
		return $this->belongsTo(Client::class,'client_id','id');
	}
	
	public function coupon():?BelongsTo
	{
		return $this->belongsTo(Coupon::class,'coupon_id','id');
	}
	public function applyCoupon(int $couponId):void
	{
		$this->coupon_id = $couponId ;
		$this->save();	
	}
}
