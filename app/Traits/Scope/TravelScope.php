<?php
namespace App\Traits\Scope;

use App\Enum\TravelStatus;
use Illuminate\Database\Eloquent\Builder;

trait TravelScope 
{
	public function scopeOnlyCompleted(Builder $builder)
	{
		return $builder->where('status',TravelStatus::COMPLETED);
	}
	public function scopeOnlyCancelled(Builder $builder)
	{
		return $builder->where('status',TravelStatus::CANCELLED);
	}
	public function scopeOnlyOnTheWay(Builder $builder)
	{
		return $builder->where('status',TravelStatus::ON_THE_WAY);
	}
	
	
}
