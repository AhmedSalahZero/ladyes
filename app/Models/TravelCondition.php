<?php

namespace App\Models;

use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasIsActive;
use App\Traits\Models\HasTransNames;
use App\Traits\Scope\HasDefaultOrderScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * * المشوار المشروط
 * * السماح بالتدخين مثلا؟
 * * السماح بصطحاب الحيوانات الاليفة؟
 */
class TravelCondition extends Model
{
    use  IsBaseModel,HasDefaultOrderScope,HasFactory,HasTransNames,HasIsActive;

    public function syncFromRequest($request)
    {
        if ($request->has('name_en')) {
            $this->name_en = $request->name_en;
        }
        if ($request->has('name_ar')) {
            $this->name_ar = $request->name_ar;
        }
		$this->is_active = $request->boolean('is_active');
		// if ($request->has('is_active')) {
        // }
        $this->save();
    }
}
