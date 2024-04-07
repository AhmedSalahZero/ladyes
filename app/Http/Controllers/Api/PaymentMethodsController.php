<?php

namespace App\Http\Controllers\Api;

use App\Enum\PaymentType;
use App\Http\Controllers\Controller;
use App\Traits\Api\HasApiResponse;
use Illuminate\Http\Request;

class PaymentMethodsController extends Controller
{
	use HasApiResponse;
    public function index(Request $request)
	{
		return $this->apiResponse(__('Data Received Successfully',[],getApiLang() )  , array_keys(PaymentType::all()) );
	}
	
	
}
