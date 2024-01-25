<?php

namespace App\Models;

use App\Models\Client;
use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasEmail;
use App\Traits\Models\HasPhone;
use App\Traits\Models\HasSingleName;
use App\Traits\Scope\HasDefaultOrderScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
* * جهات اتصال الطوارئ بمعني لو انت باعت ابنك مثلا مع سواق فا انت عايز بمجرد ما الرحله
** تبدا انه يوصلك لينك او ويب فيو بحيث تتابع مسار الرحله من علي الخريطه بحيث تكون شايف كل حاجه قدامك
** سواء للعميل او حتى للسواق بمعني ان العميل والسواق هيكون ليهم جهات اتصال طوارئ
 */
class EmergencyContact extends Model
{
    use  IsBaseModel,HasDefaultOrderScope,HasFactory,HasPhone,HasEmail,HasSingleName;

	
    public function syncFromRequest($request)
    {
        if ($request->has('name')) {
            $this->name = $request->name;
        }
        if ($request->has('phone')) {
            $this->phone = $request->phone;
        }
		if ($request->has('email')) {
            $this->email = $request->email;
        }
		if ($request->has('country_id')) {
            $this->country_id = $request->country_id;
        }

        $this->save();
		return $this ;
    }
	/**
	 * * to get drivers 
	 */
	public function drivers()
	{
		return $this->belongsToMany(Driver::class , 'model_emergency_contact','emergency_contact_id','id');
	}
	/**
	 * * to get clients 
	 */
	public function clients()
	{
		return $this->belongsToMany(Client::class , 'model_emergency_contact','emergency_contact_id','id');
	}
	
}
