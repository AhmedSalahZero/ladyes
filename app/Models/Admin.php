<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;
    use HasFactory;
    protected $table = 'admins';
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

    public function scopeActive($q){
        return $q->where('active',1);
    }

    public function employees(){
        return $this->hasMany(Admin::class,'parent_id');
    }

    public function group_permition(){
        return $this->belongsTo(PermitionGroup::class,'permission_group_id');
    }

    public function check_route_permission($route_name){
        if ($this->parent_id == 0){
            return 1;
        }else{
            $check = AdminPermitions::where('permission_group_id',$this->permission_group_id)->whereHas('link',function ($q)use ($route_name){
                return $q->where('route_name',$route_name);
            })->first();
            if ($check)
                return 1;
            else
                return 0;
        }

    }
}
