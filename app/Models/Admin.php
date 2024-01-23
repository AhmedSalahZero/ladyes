<?php

namespace App\Models;

use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasIsActive;
use App\Traits\Scope\AdminScope;
use App\Traits\Scope\HasDefaultOrderScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use IsBaseModel,HasDefaultOrderScope ,Notifiable  , HasFactory , HasIsActive , AdminScope,HasRoles;
	
    protected $guard = 'admin';

    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getAvatarAttribute(){
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://gravatar.com/avatar/$hash";
    }
	public function getAvatar()
	{
		return $this->avatar;
	}
	public function getName()
	{
		return $this->name;
	}
	public function getRoleId()
	{
		$role = $this->roles()->first() ;
		return $role ? $role->id : 0 ;
	}	
	public function getRoleName()
	{
		$role = $this->roles()->first() ;
		return $role ? $role->name : __('N/A') ;
	}
	
	public function syncFromRequest($request){
		if ($request->has('name'))
		$this->name = $request->name;
	if ($request->has('email'))
		$this->email = $request->email;
	if ($request->has('role_name'))
		$this->assignRole($request->get('role_name'));
	if ($request->filled('password'))
		$this->password = Hash::make($request->password);
	$this->is_active = $request->boolean('is_active') ;
	$this->save();
	}
	public static function getNameById($id){
		$model = self::find($id);
		return $model ? $model->getName() : __('N/A');
	}
	
}
