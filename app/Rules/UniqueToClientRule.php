<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UniqueToClientRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
	protected string $model_name ;
	protected string $error_message ;
	protected ?int $id ;
    public function __construct(string $modelName,string $errorMessage,?int $id)
    {
        $this->model_name = $modelName ;
        $this->error_message = $errorMessage ;
        $this->id = $id ;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
		$user = Request()->user('client');
		if(!$user){
			return false ;
		}
        $query  = (new ('App\Models\\'.$this->model_name))::where('client_id',$user->id)->where($attribute,$value);
		if($this->id){
			$query = $query->where('id','!=',$this->id);
		}
		return !$query->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->error_message;
    }
}
