<?php 
namespace App\Traits\Models;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait HasBasicStoreRequest 
{
	public function storeBasicForm(Request $request , array $except = ['_token','save','_method'] ):self{
		foreach($request->except($except) as $name => $value){
			$columnExist = Schema::hasColumn($this->getTable(),$name);
			if(is_object($value)){
				static::addMediaFromRequest($name)->toMediaCollection($name);
			}elseif(!is_array($value)&& (Str::startsWith($value,'is_') || Str::startsWith($value,'can_')|| Str::startsWith($value,'has_')) ){
				if($columnExist){
					$this->{$name} = $request->boolean($name);
				}
			}elseif($columnExist){
				$this->{$name} = $request->get($name);
			}
		}
		
		$this->save();
		
		return $this ; 
	}
}
