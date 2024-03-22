<?php 
namespace App\Traits\Models;
trait HasCarSize 
{
	public function scopeOnlyWithCarSize($q,int $carSizeId){
        return $q->where('size_id',$carSizeId);
    }
	
	
}
