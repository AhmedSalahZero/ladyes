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
		$payments = PaymentType::all() ;
		$paymentsFormatted = [];
		foreach($payments as $key => $name){
			$paymentsFormatted[] = [
				'id'=>$key , 
				'name'=>$name , 
				'image'=>PaymentType::images()[$key] ?? null 
			];
		}
		
		return $this->apiResponse(__('Data Received Successfully',[],getApiLang() )  , $paymentsFormatted  );
	}
	
	
}
