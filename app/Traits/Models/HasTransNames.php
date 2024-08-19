<?php 
namespace App\Traits\Models;
trait HasTransNames 
{
	public function getName($lang = null){
		$lang = $lang ?: app()->getLocale(); 
		$name = $this['name_'.$lang];
		return $name ?: __('N/A') ;
	}
	public function getFullName()
	{
		return $this->name_en . '-'.$this->name_ar;
	}
}
