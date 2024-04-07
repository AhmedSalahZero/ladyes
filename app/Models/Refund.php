<?php

namespace App\Models;

use App\Traits\Accessors\IsBaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * * اعادة تحويل الاموال بعد الغاء الرحلة مثلا
 */
class Refund extends Model
{
    use IsBaseModel ;
    use HasFactory ;
	
	protected $guarded = [
		'id'
	];
	public function client()
	{
		return $this->belongsTo(Client::class , 'client_id','id');
	}

	public function travel()
	{
		return $this->belongsTo(Travel::class,'travel_id','id');
	}
	
	/**
	 * * دا مثال علي ال 
	 * * refund
	 */
	// public function __execute()
	// {
		
	// 	/**
	// 		 * * هنعمل عمليه ري فند
	// 		 */
	// 		$refundAmount = 5 ;
	// 		$refund  = $this->refund()->create([
	// 			'client_id'=>$user->id ,
	// 			'amount'=>$refundAmount,
	// 			'note_en' => __('Refund Has Been Been Added To Your Wallet :amount :currency For Travel # :travelId', ['amount' => number_format($refundAmount), 'currency' => $currencyNameEn, 'travelId' => $this->id], 'en'),
	// 			'note_ar' => __('Refund Has Been Been Added To Your Wallet :amount :currency For Travel # :travelId', ['amount' => number_format($refundAmount), 'currency' => $currencyNameAr, 'travelId' => $this->id], 'ar'),
	// 		]);
	// 		Transaction::create(
	// 			[
	// 				'type' => TransactionType::REFUND,
	// 				'type_id' => $refund->id ,
	// 				'model_id' => $user->id,
	// 				'amount' => $refundAmount,
	// 				'model_type' => HHelpers::getClassNameWithoutNameSpace($user),
	// 				'note_en' => __('Refund Has Been Been Added To Your Wallet :amount :currency For Travel # :travelId', ['amount' => number_format($refundAmount), 'currency' => $currencyNameEn, 'travelId' => $this->id], 'en'),
	// 				'note_ar' => __('Refund Has Been Been Added To Your Wallet :amount :currency For Travel # :travelId', ['amount' => number_format($refundAmount), 'currency' => $currencyNameAr, 'travelId' => $this->id], 'ar'),
	// 				]
	// 		);
	// }
}
