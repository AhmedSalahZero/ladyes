<?php 
namespace App\Traits\Models;

/**
 * * زي مثلا هل هو سائق ولا عميل
 * * Driver , Client , etc [model name]
 */
trait HasModelType 
{
	public function getModelType()
	{
		return $this->model_type ;
	}
	public function getModelTypeFormatted()
	{
		return __($this->model_type) ;
	}
	
}
