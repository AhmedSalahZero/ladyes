<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Links extends Model
{
    use HasFactory;

    protected $table = 'links';
    protected $guarded = [];

    public function getNameAttribute(){
        if (App::isLocale('ar'))
            return $this->name_ar;
        else
            return $this->name_en;
    }

    public function sub_links(){
        return $this->hasMany(Links::class,'parent_id')->where('is_sub_menue',1);
    }

    public function sub_links_permitions(){
        return $this->hasMany(Links::class,'parent_id');
    }
}
