<?php 
namespace App\Interfaces;
interface IHaveAppNotification {
	public function sendAppNotification(string $titleEn,string $titleAr,string $messageEn,string $messageAr,string $secondaryType , int $modelId = null , string $mainType ='notification');
}
