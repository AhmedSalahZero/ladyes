<?php

namespace App\Models;

use App\Helpers\HArr;
use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasIsActive;
use App\Traits\Models\HasModelType;
use App\Traits\Models\HasSection;
use App\Traits\Models\HasTransDescriptions;
use App\Traits\Models\HasTransNames;
use App\Traits\Scope\HasDefaultOrderScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 ** التعليمات الخاصة بالعميل او السائق
 ** علي سبيل المثال 
 ** كيف اقوم بتقيم الكاابتن؟
 ** كيف يتم تسعير الرحلة ؟
 ** كيف يتم تسعير الرحلة ؟
 ** وبالتالي هي تختلف علي حسب السكشن
 ** InformationSection::class 
 */
class Information extends Model
{
    use  IsBaseModel,HasDefaultOrderScope,HasFactory,HasTransNames,HasIsActive,HasTransDescriptions,HasSection;

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
		if ($request->has('section_name')) {
            $this->section_name = $request->section_name;
        }
		$this->is_active = $request->boolean('is_active');
        $this->save();
    }
	public static function getForSection(string $sectionName , string $lang):array 
	{
		$values = self::where('section_name',$sectionName)->get(['name_'.$lang,'description_'.$lang])->toArray() ;
		/**
		 * * هنحول ال
		 * * name_en to name 
		 * * دا طلب ال 
		 * * api 
		 */
		return HArr::removeUnderscoreLangFromKeys($values);
	}
}
