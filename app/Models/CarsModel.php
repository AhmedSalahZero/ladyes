<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class CarsModel extends Model
{
    use HasFactory;
    protected $table = 'cars_models';
    protected $guarded = [];

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

    public function scopeActive($q){
        return $q->where('active',1);
    }
}
