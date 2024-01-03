<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermitionGroup extends Model
{
    use HasFactory;
    protected $table = 'permition_groups';
    protected $guarded = [];

    public function links_permissions(){
        return $this->hasMany(AdminPermitions::class,'permission_group_id');
    }

    public function is_hav_link($link_id){
        return AdminPermitions::where('permission_group_id',$this->id)->where('link_id',$link_id)->first();
    }
}
