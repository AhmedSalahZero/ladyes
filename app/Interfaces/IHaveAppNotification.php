<?php 
namespace App\Interfaces;
interface IHaveAppNotification {
	public function sendAppNotification(string $titleEn,string $titleAr,string $messageEn,string $messageAr);
}
