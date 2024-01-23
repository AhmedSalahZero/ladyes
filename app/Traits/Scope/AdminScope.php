<?php
namespace App\Traits\Scope;

use Illuminate\Database\Eloquent\Builder;

trait AdminScope 
{
	public function scopeOnlyEmployees(Builder $builder)
	{
		return $builder->where('is_parent','!=',0);
	}
	public function scopeOnlyAdmins(Builder $builder)
	{
		return $builder->where('is_parent',0);
	}
}
