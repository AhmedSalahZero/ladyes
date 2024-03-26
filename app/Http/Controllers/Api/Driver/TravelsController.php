<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\Travel;
use App\Traits\Api\HasApiResponse;
use Illuminate\Http\Request;

class TravelsController extends Controller
{
	use HasApiResponse;

	public function markAsStarted(Request $request,Travel $travel)
	{
		$travel = $travel->markAsStarted($request);
		return $this->apiResponse(__('Travel Has Been Marked AS Started',[],getApiLang()));
	}
	public function markAsCancelled(Request $request,Travel $travel)
	{
		$travel->markAsCancelled($request);
	}
	public function markAsCompleted(Request $request,Travel $travel)
	{
		$travel = $travel->markAsCompleted($request);
		return $this->apiResponse(__('Travel Has Been Marked AS Completed',[],getApiLang()));
	}
	
}
