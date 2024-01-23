<?php 
namespace App\Traits\Models;
trait HasIsActive 
{
	public function scopeOnlyIsActive($q){
        return $q->where('is_active',1);
    }
	public function getIsActive()
	{
		return (bool) $this->is_active ;
	}
	public function getIsActiveFormatted()
	{
		$isActive = $this->getIsActive();
		return $isActive ? __('Yes') : __('No');
	}
	public function toggleIsActive()
	{
		$this->is_active = ! $this->is_active ;
		$this->save();
	}
}
