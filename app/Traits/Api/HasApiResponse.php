<?php 
namespace App\Traits\Api;

use Illuminate\Http\JsonResponse;

trait HasApiResponse
{
		public function apiResponse(string $message = null ,  $data = [] , int $responseCode = 200 ):JsonResponse
		{

			$message = is_null($message) ? __('Data Received Successfully') : $message ;
			return Response()->json([
				'status'=>$responseCode ,
				'message'=>$message , 
				'data' => $data
			]);	
		}
}
