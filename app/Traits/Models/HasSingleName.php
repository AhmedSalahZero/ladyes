<?php 
namespace App\Traits\Models;
trait HasSingleName 
{
	public function getName()
	{
		return $this->name ;
	}
}
