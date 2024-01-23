<?php 
namespace App\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

trait HasFailedValidation
{
	
	public function failedValidation(Validator $validator){
		throw new HttpResponseException(response()->json([
			'status'=> Response::HTTP_BAD_REQUEST ,
			'message'=>$validator->errors()->first()
		],Response::HTTP_BAD_REQUEST));
	}
}
