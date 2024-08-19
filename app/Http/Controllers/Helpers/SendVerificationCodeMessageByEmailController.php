<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;

class SendVerificationCodeMessageByEmailController extends Controller
{
    public function send(Request $request){
		$modelType = $request->get('model_type');
		$model = ('\App\Models\\'.$modelType)::find($request->get('model_id'));
		if(!$model){
			return redirect()->back()->with('fail',__('Driver Not Found'));
		}
		$responseArr = $model->sendVerificationCodeViaEmail();
		$status = isset($responseArr['status']) && $responseArr['status'] ? 'success' : 'fail';
		$message = isset($responseArr['message']) && $responseArr['message'] ? $responseArr['message'] : null;
		return redirect()->back()->with($status,$message);
	}
}
