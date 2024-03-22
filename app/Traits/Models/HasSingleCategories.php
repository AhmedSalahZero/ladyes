<?php 
namespace App\Traits\Models;
trait HasSingleCategories 
{
	
	public function getCategory(){
		$category = $this['category'];
		return $category ?: __('N/A') ;
	}
}
