<?php

namespace App\Models;

use App\Enum\DiscountType;
use App\Helpers\HStr;
use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasDiscountAmountWithDiscountType;
use App\Traits\Models\HasExpiredDate;
use App\Traits\Models\HasStartAndEndDate;
use App\Traits\Models\HasTransNames;
use App\Traits\Scope\HasDefaultOrderScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
** كوبونات الخصم الخاصة بالعميل 
** يمكن انشائها من الادمن ويكون ليها وقت انتهاء يحددة الادمن اذا اراد 
** وكمان بيتم انشائها تلقائي بعد كل طلب للعميل بحيث ممكن يديها لعميل تاني يستخدمها
*/
class Coupon extends Model
{
    use  IsBaseModel,HasDefaultOrderScope,HasFactory,HasTransNames,HasExpiredDate,HasStartAndEndDate,HasDiscountAmountWithDiscountType;
	/**
	 * * عدد المرات اللي من الممكن استخدام هذا الكوبون فيها .. بمعنى هل هو صالح للاستخدام مرة ولا اتنين ولا ثلاثه
	 */
	public function getNumberOfUses()
	{
		return (int)$this->number_of_uses?:1;
	}
	/**
	 * * عدد المرات اللي تم فيها استخدام هذا الكوبون في الرحلات فعليا من قبل العملاء
	 */
	public function getNumberOfApplies():int 
	{
		return $this->travels->count() ; 
	}
	
	/**
	 * * هل الكوبون صالح للاستخدام حاليا ؟
	 * * اولا تاريخة ( ان وجد) ما يكونش منتهي .. وكمان ما يكونش تم استخدامه اقصى عدد من المرات
	 */
	public function isAvailableForUsing( ):bool 
	{
		$isAvailable = $this->getIsAvailable() ; 
		$canBeUsed = $this->getNumberOfApplies() < $this->getNumberOfUses();
		return $isAvailable && $canBeUsed ;
	}
	/**
	 * * هل يمكن تطبيق هذا الكوبون لهذا العميل؟ لان لو الكوبون متاح بس اليوزر استخدمه قبل كدا يبقي ما يقدرش يستخدمة تاني
	 */
	public function canBeAppliedForClient(int $clientId):bool
	{
		$isAvailable = $this->isAvailableForUsing();
		$clientUsedItBefore = $this->travels->where('client_id',$clientId)->exists();
		return $isAvailable && ! $clientUsedItBefore;
	}
	// yyyy
	
	
	public function getCode():string 
	{
		return $this->code ;
	}
    public function syncFromRequest($request)
    {
        if ($request->has('name_en')) {
            $this->name_en = $request->name_en;
        }
        if ($request->has('name_ar')) {
            $this->name_ar = $request->name_ar;
        }
		if ($request->has('discount_amount')) {
			$this->discount_amount = $request->discount_amount;
        }
		if ($request->has('start_date')) {
			$this->start_date = $request->start_date;
        }
		if ($request->has('end_date')) {
			$this->end_date = $request->end_date;
        }
		$this->discount_type = DiscountType::FIXED;
		$this->code = HStr::generateUniqueCodeForModel(HStr::getClassNameWithoutNameSpace($this));
        $this->save();
    }
	public function travels():HasMany
	{
		return $this->hasMany(Travel::class,'coupon_id','id');
	}
	

}
