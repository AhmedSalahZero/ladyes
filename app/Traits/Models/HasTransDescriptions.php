<?php 
namespace App\Traits\Models;
trait HasTransDescriptions 
{
	public function getDescription($lang = null){
		$lang = $lang ?: app()->getLocale(); 
		$description = $this['description_'.$lang];
		return $description ?: __('N/A') ;
	}
	
}
