<?php 
namespace App\Traits\Models;
trait HasSingleDescription 
{
	public function getDescription(){
		$description = $this['description'];
		return $description ?: __('N/A') ;
	}
	
}
