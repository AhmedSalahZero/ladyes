<?php
namespace App\Services\SMS;

class JawalyService
{
    
    const API_KEY = 'api key';
    const API_SECRET = 'api secret';
    protected $authHeader = [];
    public function __construct()
    {
        $app_id = self::API_KEY;
        $app_sec = self::API_SECRET;
        $app_hash = base64_encode("{$app_id}:{$app_sec}");

        $this->authHeader = [
            "Accept: application/json",
            "Content-Type: application/json",
            "Authorization: Basic {$app_hash}"
        ];
    }
	public function send(array $messages):array 
	{
		// $messages = [
		// 	"messages" => [
		// 		[
		// 			"text" => "test",
		// 			"numbers" => ["9665000000"],
		// 			"sender" => "4jawaly"
		// 		]
		// 	]
		// ];
		
		
		
		$url = "https://api-sms.4jawaly.com/api/v1/account/area/sms/send";
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($messages));
		curl_setopt($curl, CURLOPT_HTTPHEADER, $this->authHeader);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
		$response = curl_exec($curl);
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
		
		$response_json = json_decode($response, true);
		$responseMessage = null ;
		if ($statusCode == 200) {
			if (isset($response_json["messages"][0]["err_text"])) {
				$responseMessage =  $response_json["messages"][0]["err_text"];
			} else {
				$responseMessage= "تم الارسال بنجاح  " . " job id:" . $response_json["job_id"];
			}
		} elseif ($statusCode == 400) {
			$responseMessage= $response_json["message"];
		} elseif ($statusCode == 422) {
			$responseMessage= "نص الرسالة فارغ";
		} else {
			$responseMessage =  "محظور بواسطة كلاودفلير. Status code: {$statusCode}";
		}
		return [
			'statusCode'=>$statusCode,
			'responseMessage'=>$responseMessage
		];
	}
}
