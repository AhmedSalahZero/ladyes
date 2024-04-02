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
    public function formatNumber(string $phone , string $countryIso2)
    {
		// it takes 01025894984 and eg 
		// returns +201025894984
		
		
        // format Number As E164 ;
        $swissNumberStr = $phone;

            $swissNumberProto = $this->phoneUtil->parse($swissNumberStr, strtoupper($countryIso2));

         return $this->phoneUtil->format($swissNumberProto, PhoneNumberFormat::E164);

    }

    public function isValidNumber(string $phoneNumber , $countryIso2)
    {
        try{
            $swissNumberProto = $this->phoneUtil->parse($phoneNumber, strtoupper($countryIso2));
        }
        catch (\Exception $exception)
        {
            return false ;
        }

        return $this->phoneUtil->isValidNumber($swissNumberProto);
    }

//    public function checkIfPhoneHasWhatsapp(string $phone , string $countryIso2)
//    {
//
//        $formattedNumberInE161 = $this->formatNumber($phone,$countryIso2);
//
//        $isValid = $this->isValidNumber($phone , $countryIso2);
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
