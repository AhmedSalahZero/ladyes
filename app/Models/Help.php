<?php

namespace App\Models;

use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasIsActive;
use App\Traits\Models\HasModelType;
use App\Traits\Models\HasTransDescriptions;
use App\Traits\Models\HasTransNames;
use App\Traits\Scope\HasDefaultOrderScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 ** قسم المساعدة سواء للعميل او السائق
 ** علي سبيل المثال كيف اغير الباسورد ؟ كيف يتم خصم الرسوم
 ** او اوجهه مشكله في تغير الباسورد
 ** او كيف يتم خصم نسبة الاستقطاع
 */
class Help extends Model
{
    use  IsBaseModel;
    use HasDefaultOrderScope;
    use HasFactory;
    use HasTransNames;
    use HasIsActive;
    use HasModelType;
    use HasTransDescriptions;

    public function syncFromRequest($request)
    {
        if ($request->has('name_en')) {
            $this->name_en = $request->name_en;
        }
        if ($request->has('name_ar')) {
            $this->name_ar = $request->name_ar;
        }
        if ($request->has('description_en')) {
            $this->description_en = $request->description_en;
        }
        if ($request->has('description_ar')) {
            $this->description_ar = $request->description_ar;
        }
        if ($request->has('model_type')) {
            $this->model_type = $request->model_type;
        }
        $this->is_active = $request->boolean('is_active');
        // if ($request->has('is_active')) {
        // }
        $this->save();
    }
}
