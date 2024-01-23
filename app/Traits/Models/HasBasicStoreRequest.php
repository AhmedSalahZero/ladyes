<?php 
namespace App\Traits\Models;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait HasBasicStoreRequest 
{
	public function storeBasicForm(Request $request , array $except = ['_token','save','_method'] ):self{
		foreach($request->except($except) as $name => $value){
			if(is_object($value)){
				static::addMediaFromRequest($name)->toMediaCollection($name);
			}elseif(Str::startsWith($value,'is_') || Str::startsWith($value,'can_')|| Str::startsWith($value,'has_')){
				$this->{$name} = $request->boolean($name);
			}else{
				$this->{$name} = $request->get($name);
			}
		}
		
		$this->save();
		
		return $this ; 
	}
}
