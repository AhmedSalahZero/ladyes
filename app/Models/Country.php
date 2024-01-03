<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Country extends Model
{
    use HasFactory;
    protected $table = 'countries';
    protected $guarded = [];

    public function cities(){
        return $this->hasMany(Country::class,'country_id');
    }

    public function areas(){
        return $this->hasMany(Country::class,'city_id');
    }

    public function getNameAttribute(){
        if (App::isLocale('ar'))
            return $this->name_ar;
        else
            return $this->name_en;
    }

    public function api_name($lang){
        if ($lang == 'ar')
            return $this->name_ar;
        else
            return $this->name_en;
    }
}
