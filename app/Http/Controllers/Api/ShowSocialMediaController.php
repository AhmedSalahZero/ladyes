<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\Api\HasApiResponse;
use Illuminate\Http\Request;

class ShowSocialMediaController extends Controller
{
	use HasApiResponse;
    public function index(Request $request)
	{
		return  $this->apiResponse(__('Data Received Successfully',[],getApiLang()), [
			'whatsapp'=>getSetting('whatsapp'),
			'email'=>getSetting('email'),
			'twitter'=>getSetting('twitter_url')
		]);
	}
	
}
