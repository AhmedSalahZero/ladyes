<?php

namespace App\Models;

use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasAmountWithDiscountType;
use App\Traits\Models\HasExpiredDate;
use App\Traits\Models\HasIsActive;
use App\Traits\Models\HasStartAndEndDate;
use App\Traits\Models\HasStartAndEndDateTime;
use App\Traits\Models\HasTransNames;
use App\Traits\Scope\HasDefaultOrderScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 ** العرض الترويجي هو عباره عن فتره بيكون نازل فيها خصم معين للسواق وليكن مثلا عشرين في المية 
 ** او خمسين ريال مثلا
*/
class Promotion extends Model
{
    use  IsBaseModel,HasDefaultOrderScope,HasFactory,HasTransNames,HasExpiredDate,HasStartAndEndDate,HasAmountWithDiscountType;
	
    public function syncFromRequest($request)
    {
        if ($request->has('name_en')) {
            $this->name_en = $request->name_en;
        }
        if ($request->has('name_ar')) {
            $this->name_ar = $request->name_ar;
        }
		if ($request->has('discount_type')) {
            $this->discount_type = $request->discount_type;
        }
		if ($request->has('amount')) {
            $this->amount = $request->amount;
        }
		if ($request->has('start_date')) {
            $this->start_date = $request->start_date;
        }
		if ($request->has('end_date')) {
            $this->end_date = $request->end_date;
        }
		// $this->is_active = $request->boolean('is_active');
		
        $this->save();
    }
}
