<?php

namespace App\Services\Payments;

use Ramsey\Uuid\Type\Decimal;

class MyFatoorahService
{
    private   $requestType  , $apiToken , $apiPointUrlStatus ;

    public function __construct()
    {
        $this->apiPointUrlStatus =env('myfatoorah_base_url').'v2/getPaymentStatus';
        $this->requestType = 'POST';
        $this->apiToken = 'Authorization: Bearer '. env('MYFATOORAH_TOKEN');
    }

    public function sendPayment($apiUrl,$data)
    {
        $json = $this->callAPI($apiUrl , $data);
        return $json->Data;
    }
    public function getPaymentStatus($apiUrl , $data)
    {
        return $this->callAPI($apiUrl , $data);
    }
	
	public function storeNewOrder(float $price , int $productId,string $productName ,int $clientIdentifier, string $clientName,string $clientPhone , string $clientEmail)
	{
		$apiUrlSendPayment = env('myfatoorah_base_url').'v2/SendPayment' ;
        // $apiUrlPaymentStatus = env('myfatoorah_base_url').'v2/getPaymentStatus' ;
        $fatoorahData  = [
            'CustomerName'       => $clientName,
            'NotificationOption' => 'Lnk',
            'InvoiceValue'       => $price ,
			'MobileCountryCode' => '+02',
            'CustomerMobile'     => $clientPhone,
            'CustomerEmail'      => $clientEmail,
            'CallBackUrl'        => Request()->root().'/pay-course-success',
            'ErrorUrl'           => Request()->root().'/pay-course-error', //or 'https://example.com/error.php'
            'Language'           => getApiLang(), //or 'ar',
            'DisplayCurrencyIso'=>env('DISPLAY_CURRENCY') ,
            'UserDefinedField'=>$clientIdentifier,
            'CustomerReference'=>$productId,
            // 'InvoiceItems'=>[
            //     [
            //         "ItemName"=> $productName,
            //         "Quantity"=> 1,
            //         "UnitPrice"=> $price
            //     ],
            // ]
        ];
       $data =  $this->sendPayment($apiUrlSendPayment , $fatoorahData);
	   return $data->InvoiceURL ; 
	}

    public function callAPI($pointUrl , $data)
    {
        $curl = curl_init($pointUrl);
        curl_setopt_array($curl, array(
			CURLOPT_CUSTOMREQUEST  => $this->requestType,
            CURLOPT_POSTFIELDS     => json_encode($data),
            CURLOPT_HTTPHEADER     => array($this->apiToken, 'Content-Type: application/json'),
            CURLOPT_RETURNTRANSFER => true,
        ));
		
        $response = curl_exec($curl);
        $curlErr  = curl_error($curl);
		
        curl_close($curl);
		
        if ($curlErr) {
            die("Curl Error: $curlErr");
        }

        $error = $this->handleError($response);
        if ($error) {
            die("Error: $error");
        }

        return json_decode($response);
    }
    public function handleError($response) {

        $json = json_decode($response);
        if (isset($json->IsSuccess) && $json->IsSuccess == true) {
            return null;
        }

        if (isset($json->ValidationErrors) || isset($json->FieldsErrors)) {
            $errorsObj = isset($json->ValidationErrors) ? $json->ValidationErrors : $json->FieldsErrors;
            $blogDatas = array_column($errorsObj, 'Error', 'Name');

            $error = implode(', ', array_map(function ($k, $v) {
                return "$k: $v";
            }, array_keys($blogDatas), array_values($blogDatas)));
        } else if (isset($json->Data->ErrorMessage)) {
            $error = $json->Data->ErrorMessage;
        }

        if (empty($error)) {
            $error = (isset($json->Message)) ? $json->Message : (!empty($response) ? $response : 'API key or API URL is not correct');
        }

        return $error;
    }





}
