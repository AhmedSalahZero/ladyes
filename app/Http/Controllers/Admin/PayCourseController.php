<?php

namespace App\Http\Controllers;

use App\Services\Payments\MyFatoorahService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PayCourseController extends Controller
{



    public function successCallback(Request $request)
    {
        $myFatoorahService= new MyFatoorahService();
        $apiUrlPaymentStatus = env('myfatoorah_base_url').'v2/getPaymentStatus' ;
        $status = $myFatoorahService->getPaymentStatus($apiUrlPaymentStatus , [
            'Key'     => strval(Request('paymentId')),
            'KeyType' => 'paymentId'
        ]);

        if($status->Data->InvoiceStatus === 'Paid')
        {
            Order::create([
                'user_id'=>$status->Data->UserDefinedField ,
                'course_id'=>$status->Data->CustomerReference ,
                'invoice_id'=>$status->Data->InvoiceId ,
                'payment_id'=>strval(Request('paymentId')) ,
                'status'=>Order::ACTIVE,
                'price'=>$status->Data->InvoiceValue
            ]);
            return redirect()->route('academy.courses')->with('success' , __('Purchasing Done Successfully'));
        }
        return redirect()->route('home.page')->with('fail',__('Something Wrong Happened .. Please Contact An Admin'));
    }
    public function errCallback()
    {
        return redirect()->route('home.page')->with('fail',__('Something Wrong Happened While Processing To Payment'));

    }
}
