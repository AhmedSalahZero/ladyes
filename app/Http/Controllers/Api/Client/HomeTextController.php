<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Traits\Api\HasApiResponse;

/**
 * * عباره عن النص اللي اول ما تفتح تطبيق العميل تلاقيهم
 */
class HomeTextController extends Controller
{
	use HasApiResponse;
	
	public function view()
	{
		
		$data['body'] = [
			'take_safety'=>getSetting('take_safety_'.getApiLang()),
			'select_your_route'=>getSetting('select_your_route_'.getApiLang()),
			'choose_the_appropriate_offer'=>getSetting('choose_the_appropriate_offer_'.getApiLang()),
			'follow_capitan_path'=>getSetting('follow_capitan_path_'.getApiLang()),
		] ;

		return $this->apiResponse(__('Data Received Successfully'),$data);
	}
}
