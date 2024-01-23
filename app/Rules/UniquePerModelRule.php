<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniquePerModelRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
	protected $table_name  , $conditions , $errorMessage ;
    public function __construct(string $tableName , array $conditions , string $errorMessage=null  )
    {
		$this->table_name = $tableName;
		$this->conditions = $conditions;
		$this->errorMessage = $errorMessage  ; 
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
        $table = DB::table($this->table_name);
		foreach($this->conditions as $condition){
			$table->where($condition[0] , $condition[1] , $condition[2]);
			if(isset($condition[3])){
				$table->where($condition[3][0] , $condition[3][1] , $condition[3][2]);
			}
		}
		return !$table->where($attribute,$value)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errorMessage;
    }
}
