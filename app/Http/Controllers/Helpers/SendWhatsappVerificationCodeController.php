<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;

class SendWhatsappVerificationCodeController extends Controller
{
    public function send(Request $request){
		$driver = Driver::find($request->get('driver_id'));
		if(!$driver){
			return redirect()->back()->with('fail',__('Driver Not Found'));
		}
		return $driver->sendVerificationCodeMessage();
	}
}