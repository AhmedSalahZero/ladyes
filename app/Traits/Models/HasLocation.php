<?php 
namespace App\Traits\Models;
trait HasLocation 
{
	public function scopeOnlyHasLocation($q){
        return $q->whereNotNull('location');
    }
	
	
}
