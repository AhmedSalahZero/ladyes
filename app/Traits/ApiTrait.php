<?php

namespace App\Traits;

use App\Models\UsersCard;
use App\Models\VendorPlane;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
trait ApiTrait
{
    public function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

    public function errorResponse($msg, $code = 401)
    {
        return response()->json([
            'status' => $code,
            'msg' => $msg,
        ]);
    }

    public function successResponse($msg, $code = 200)
    {
        return response()->json([
            'status' => $code,
            'msg' => $msg,
        ]);
    }

    public function dataResponse($msg, $data, $code = 200)
    {
        return response()->json([
            'status' => $code,
            'msg' => $msg,
            'data' => $data,
        ]);
    }


    public function myfatorah_payment($request)
    {
        $cardData = null;

        $apiURL = 'https://apitest.myfatoorah.com';
        $apiKey = 'rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL';

        $postFields = [
            //Fill required data
            'paymentMethodId' => '20',
            'InvoiceValue' => $request->price,
            'CallBackUrl' => 'https://nawaem.com/success',
            'ErrorUrl' => 'https://nawaem.com/faild',
        ];

        $data = executePayment($apiURL, $apiKey, $postFields);
        $paymentURL = $data->PaymentURL;

        if ($request->card_id && $request->card_id > 0) {
            $cardData = UsersCard::find($request->card_id);
            if (!$cardData)
                return $this->errorResponse(__('msg.card_not_found'));
        }

        $cardInfo = [
            'PaymentType' => 'card',
            'Bypass3DS' => true,
            "SaveToken" => true,
            'Card' => [
                'Number' => $cardData && $cardData->card_number ? decrypt($cardData->card_number) : $request->card_number,
                'ExpiryMonth' => $cardData && $cardData->ex_month ? $cardData->ex_month : $request->ex_month,
                'ExpiryYear' => $cardData && $cardData->ex_year ? $cardData->ex_year : $request->ex_year,
                'SecurityCode' => $cardData && $cardData->cvv ? $cardData->cvv : $request->cvv,
                'CardHolderName' => $cardData && $cardData->holder_name ? $cardData->holder_name : $request->holder_name
            ]
        ];

        return directPayment($paymentURL, $apiKey, $cardInfo);
    }
	
	

	
}
