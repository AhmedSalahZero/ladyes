<?php

namespace App\Http\Controllers\Api\Driver;

use App\Helpers\HHelpers;
use App\Http\Controllers\Controller;
use App\Models\Help;
use App\Traits\Api\HasApiResponse;


class HelpController extends Controller
{
	use HasApiResponse;
	
	public function viewForDriver()
	{
		$lang  = getApiLang();
		$data['body'] = Help::getForModelTypeFormatted(HHelpers::getClassNameWithoutNameSpace(Request()->user()),$lang);
		return $this->apiResponse(__('Data Received Successfully'),$data);
	}
}
