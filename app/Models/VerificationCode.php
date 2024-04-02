<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    use HasFactory;
	
	protected $fillable = [
		'user_type',
		'country_iso2',
		'phone',
		'code'
	];
	
	public function findByCodeAndPhone($userType,$countryCode , $phone)
	{
		return self::where('country_code',$countryCode)->where('phone',$phone)->where('user_type',$userType)->first();
		
	}
}
