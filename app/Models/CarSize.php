<?php

namespace App\Models;

use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasTransNames;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarSize extends Model
{
    use HasFactory,IsBaseModel,HasTransNames;
	public function drivers()
	{
		return $this->hasMany(Driver::class,'size_id','id');
	}
	
}
