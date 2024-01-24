<?php

namespace App\Models;

use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasIsActive;
use App\Traits\Models\HasTransNames;
use App\Traits\Scope\HasDefaultOrderScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
** شروط الالغاء ( سواء للعميل او للسائق زي مثلا ان السائق جه متاخر او العيل ماجاش الخ)
 */
class CancellationReason extends Model
{
    use  IsBaseModel,HasDefaultOrderScope,HasFactory,HasTransNames,HasIsActive;

	public function getModelType()
	{
		return $this->model_type ;
	}
	public function getModelTypeFormatted()
	{
		return __($this->model_type) ;
	}
	
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
		// if ($request->has('is_active')) {
        // }
        $this->save();
    }
}
