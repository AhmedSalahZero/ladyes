<?php 
namespace App\Traits\Models;

use App\Models\Fine;

trait HasFine 
{
	public function hasFines():bool
	{
		return $this->getUnpaidFines() > 0;
	}
	public function fines()
	{
		return $this->hasMany(Fine::class,'model_id','id');
	}
	public function getUnpaidFines()
	{
		return $this->fines->where('is_paid',0);
	}
	public function getPaidFines()
	{
		return $this->fines->where('is_paid',1);
	}
}
