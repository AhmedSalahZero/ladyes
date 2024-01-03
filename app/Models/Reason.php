<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Reason extends Model
{
    use HasFactory;
    protected $table = 'reasons';
    protected $guarded = [];

    public function getTitleAttribute(){
        if (App::isLocale('ar'))
            return $this->title_ar;
        else
            return $this->title_en;
    }

    public function api_title($lang){
        if ($lang == 'ar')
            return $this->title_ar;
        else
            return $this->title_en;
    }


}
