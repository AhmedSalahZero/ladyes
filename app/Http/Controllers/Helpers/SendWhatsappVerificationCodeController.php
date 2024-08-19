<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SendWhatsappVerificationCodeController extends Controller
{
    public function send(Request $request){
		
		$modelType = $request->get('model_type');
		$driver = ('\App\Models\\'.$modelType)::find($request->get('model_id'));
		if(!$driver){
			return redirect()->back()->with('fail',__($modelType.' Not Found'));
		}
		$responseArr = $driver->sendVerificationCodeMessage($driver->getVerificationCode(),false , true ,false ) ;
		$status = isset($responseArr['status']) && $responseArr['status'] ? 'success' : 'fail';
		$message = isset($responseArr['message']) && $responseArr['message'] ? $responseArr['message'] : null;
		return redirect()->back()->with($status,$message);
	}
}
