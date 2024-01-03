<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRate extends Model
{
    use HasFactory;
    protected $table = 'user_rates';
    protected $guarded = [];

    public function deal(){
        return $this->belongsTo(Deal::class,'deal_id');
    }
}
