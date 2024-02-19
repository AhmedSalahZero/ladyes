<?php

namespace App\Http\Controllers\Api\Driver;

use App\Enum\InformationSection;
use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Information;
use App\Traits\Api\HasApiResponse;


class InformationController extends Controller
{
	use HasApiResponse;
	
	public function viewAfterSignupMessage()
	{
		$lang  = getApiLang();
		$section = Request()->user() instanceof Driver  ? InformationSection::AFTER_DRIVER_SIGNUP : InformationSection::CLIENT_PROFILE;  
		$data['header'] = getSetting('after_signup_message_'.$lang) ;
		$data['body'] = Information::getForSection($section,$lang);
		return $this->apiResponse(__('Data Received Successfully'),$data);
	}
}
