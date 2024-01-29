<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\ServiceProvider;

class MacrosServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

   
    public function boot()
    {
		// collection macros
        Collection::macro('formattedForSelect',function(bool $isFunction , string $idAttrOrFunction ,string $titleAttrOrFunction ){
			/**
			 * @var Collection $this
			 */
			return $this->map(function($item) use ($isFunction , $idAttrOrFunction ,$titleAttrOrFunction ){
				return [
					'value' => $isFunction ? $item->$idAttrOrFunction() : $item->{$idAttrOrFunction} ,
					'title' => $isFunction ? $item->$titleAttrOrFunction() : $item->{$titleAttrOrFunction}
				];
			})->toArray();
		});
		
		// query builder macros 
		
		Builder::macro('onlyAvailable', function(string $date){
			/**
			 * @var Builder $this
			 */
			return $this->where('start_date','<=',$date)->where('end_date','>',$date);
		});
		
    }
}
