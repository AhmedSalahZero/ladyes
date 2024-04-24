<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SliderResource;
use App\Models\Slider;
use App\Traits\Api\HasApiResponse;

class SlidersController extends Controller
{
	use HasApiResponse;
	
	public function view()
	{
		return $this->apiResponse(__('Data Received Successfully'),SliderResource::collection(Slider::all())->toArray(Request()));
	}
}
