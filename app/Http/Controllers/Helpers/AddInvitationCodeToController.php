<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;

class AddInvitationCodeToController extends Controller
{
    public function attach(Request $request)
	{
		$code = $request->get('invitation_code');
		$senderIdOrEmailOrPhone = $request->get('sender_id_or_email_or_phone') ;
		$sender = Driver::findByIdOrEmailOrPhone($senderIdOrEmailOrPhone);
	
		$receiver = Driver::find($request->get('receiver_id'));
		if(!$sender){
			return redirect()->back()->with('fail',__('Invalid Sender'));
		}
		if($sender->getInvitationCode() != $code || $sender->id == $receiver->id ){
			return redirect()->back()->with('fail',__('Invalid Invitation Code'));
		}
		$result = $receiver->attachInvitationCode($sender->id,$code);
		$status = $result['status'] ? 'success' : 'fail' ;
		return redirect()->back()->with($status ,$result['message']);
		
		
	}
}
