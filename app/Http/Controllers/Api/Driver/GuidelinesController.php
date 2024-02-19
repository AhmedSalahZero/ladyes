<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Traits\Api\HasApiResponse;

/**
 * * ارشادات التطبيق .. مثل عاملي الجميع بلطلق واحترام
 */
class GuidelinesController extends Controller
{
	use HasApiResponse;
	
	public function view()
	{
		$lang  = getApiLang();
		$data['header'] = getSetting('app_guideline_into_'.$lang) ;
		$data['body'] = getSetting('app_guideline_items_'.$lang) ;
		$data['footer'] = getSetting('app_guideline_outro_'.$lang) ;
		return $this->apiResponse(__('Data Received Successfully'),$data);
	}
}
