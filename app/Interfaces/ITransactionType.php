<?php 
namespace App\Interfaces;
interface ITransactionType {
	public function generateBasicNotificationMessage(float $amount ,string $currencyNameFormatted, string $lang  ):string ; 
}
