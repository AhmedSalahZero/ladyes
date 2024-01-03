<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Settings extends Model
{
    use HasFactory;
    protected $table = 'settings';
    protected $guarded = [];


    public function api_instructions($lang){
        if ($lang == 'ar')
            return $this->instructions_ar;
        else
            return $this->instructions_en;
    }
}
