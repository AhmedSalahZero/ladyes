<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Chatify\Traits\UUID;

class ChMessage extends Model
{
    use UUID;
    protected $guarded = [];
    public function from_user(){
        return $this->belongsTo(User::class,'from_id');
    }

    public function to_user(){
        return $this->belongsTo(User::class,'to_id');
    }
}
