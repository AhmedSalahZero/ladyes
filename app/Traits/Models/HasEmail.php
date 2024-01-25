<?php 
namespace App\Traits\Models;

trait HasEmail 
{
	public function getEmail()
	{
		return $this->email ;
	}
	
}
