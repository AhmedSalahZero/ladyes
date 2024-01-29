<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;

class SendSmsVerificationCodeController extends Controller
{
    public function send(Request $request){
		$driver = Driver::find($request->get('driver_id'));
		if(!$driver){
			return redirect()->back()->with('fail',__('Driver Not Found'));
		}
		$responseArr = $driver->sendVerificationCodeMessage(true , false ,false) ;
		$status = isset($responseArr['status']) && $responseArr['status'] ? 'success' : 'fail';
		$message = isset($responseArr['message']) && $responseArr['message'] ? $responseArr['message'] : null;
		return redirect()->back()->with($status,$message);
	}
}
