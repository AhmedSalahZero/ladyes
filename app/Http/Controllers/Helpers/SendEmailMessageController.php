<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Controller;
use App\Mail\SendMessageMail;
use App\Services\PhoneNumberService;
use App\Services\Whatsapp\WhatsappService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendEmailMessageController extends Controller
{
    public function send(Request $request){
		$email = $request->get('email');
		$receiverName = $request->get('name');
		$subject = $request->get('subject');
		$textMessage = $request->get('message');
		try{
			Mail::to($email)->send(new SendMessageMail($email,$receiverName,$subject,$textMessage));
		}
		catch(\Exception $e){
			
			return redirect()->back()->with('fail',__('Failed To Send Email Message') . ' <br> ' . $e->getMessage() );
		}
		return redirect()->back()->with('success',__('Email Message Has Been Sent Successfully') );
		
	}
}
