<?php

namespace App\Services;

use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class PhoneNumberService
{
    // https://github.com/giggsey/libphonenumber-for-php

    private $phoneUtil ;

    public function __construct()
    {
        $this->phoneUtil =  PhoneNumberUtil::getInstance();
    }
    public function formatNumber(string $phone , string $countryCode)
    {
		// it takes 01025894984 and eg 
		// returns +201025894984
		
		
        // format Number As E164 ;
        $swissNumberStr = $phone;

            $swissNumberProto = $this->phoneUtil->parse($swissNumberStr, strtoupper($countryCode));

         return $this->phoneUtil->format($swissNumberProto, PhoneNumberFormat::E164);

    }

    public function isValidNumber(string $phoneNumber , $countryCode)
    {
        try{
            $swissNumberProto = $this->phoneUtil->parse($phoneNumber, strtoupper($countryCode));
        }
        catch (\Exception $exception)
        {
            return false ;
        }

        return $this->phoneUtil->isValidNumber($swissNumberProto);
    }

//    public function checkIfPhoneHasWhatsapp(string $phone , string $countryCode)
//    {
//
//        $formattedNumberInE161 = $this->formatNumber($phone,$countryCode);
//
//        $isValid = $this->isValidNumber($phone , $countryCode);
//
//        if($isValid) {
//            $wassengerService = new WassengerService();
//
//            $res  = $wassengerService->sendApiRequestFor('https://api.wassenger.com/v1/numbers/exists','POST',[
//                'phone'=>$formattedNumberInE161,
//            ]);
//
//            if(isset($res) && $res->exists) {
//                return true ;
//            }
//
//        }
//
//        return false ;
//    }



}
