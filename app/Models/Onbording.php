<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Onbording extends Model
{
    use HasFactory;
    protected $table = 'onbordings';
    protected $guarded = [];

    public function getTitleAttribute(){
        if (App::isLocale('ar'))
            return $this->title_ar;
        else
            return $this->title_en;
    }

    public function getDescAttribute(){
        if (App::isLocale('ar'))
            return $this->desc_ar;
        else
            return $this->desc_en;
    }

    public function api_title($lang){
        if ($lang == 'ar')
            return $this->title_ar;
        else
            return $this->title_en;
    }

    public function api_desc($lang){
        if ($lang == 'ar')
            return $this->desc_ar;
        else
            return $this->desc_en;
    }
    public function scopeActive($q){
        return $q->where('active',1);
    }
}
