<?php 
namespace App\Traits\Models;
trait HasTransIntro 
{
	public function getIntro($lang = null){
		$lang = $lang ?: app()->getLocale(); 
		$intro = $this['intro_'.$lang];
		return $intro ?: __('N/A') ;
	}
	public function getOutro($lang = null){
		$lang = $lang ?: app()->getLocale(); 
		$outro = $this['outro_'.$lang];
		return $outro ?: __('N/A') ;
	}
	
}
