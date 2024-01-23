<?php
namespace App\Traits\Scope;

use Illuminate\Database\Eloquent\Builder;

trait HasDefaultOrderScope 
{
	public function scopeDefaultOrdered(Builder $builder)
	{
		return $builder->orderBy('id','DESC');
	}
}
