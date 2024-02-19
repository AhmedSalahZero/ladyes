<?php 
namespace App\Traits\Api;

use Illuminate\Http\JsonResponse;

trait HasApiResponse
{
		public function apiResponse(string $message , array $data = [] , int $responseCode = 200 ):JsonResponse
		{
			return Response()->json([
				'status'=>$responseCode ,
				'message'=>$message , 
				'data' => $data
			]);	
		}
}
