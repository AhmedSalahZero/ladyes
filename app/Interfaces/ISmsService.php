<?php 
namespace App\Interfaces;
interface ISmsService {
	public function sendSmsMessage(string $phone , string $countryCode , string $message ):array ;
}
