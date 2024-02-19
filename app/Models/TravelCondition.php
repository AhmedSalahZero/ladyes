<?php

namespace App\Models;

use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasIsActive;
use App\Traits\Models\HasModelType;
use App\Traits\Models\HasTransNames;
use App\Traits\Scope\HasDefaultOrderScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * * المشوار المشروط
 * * السماح بالتدخين مثلا؟
 * * السماح بصطحاب الحيوانات الاليفة؟
 * * شروط الرحلة تكون خاصة بالسائق و العميل بمعني ان السائق بيكون ليه شروط والعميل ايضا بيكون ليه شروط
 * * طب انا كعميل هيجيلي مين من السائقين ؟ هيجي الكل بس مترتبيب بناء علي 
 * * مين بيحقق شروط اكثر من شروطي ولو بيحقق كله يبقي السواق دا يجيلي الاول الاول
 */
class TravelCondition extends Model
{
    use  IsBaseModel,HasDefaultOrderScope,HasFactory,HasTransNames,HasIsActive,HasModelType;

    public function syncFromRequest($request)
    {
        if ($request->has('name_en')) {
            $this->name_en = $request->name_en;
        }
        if ($request->has('name_ar')) {
            $this->name_ar = $request->name_ar;
        }
		if ($request->has('model_type')) {
            $this->model_type = $request->model_type;
        }
		$this->is_active = $request->boolean('is_active');
		
        $this->save();
    }
}
