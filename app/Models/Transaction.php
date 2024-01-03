<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';
    protected $guarded = [];

    public function getTypeNameAttribute()
    {
        switch ($this->type_id) {
            case 1:
                return __('create_order');
            case 2:
                return __('add_balance');
            case 3:
                return __('pay_by_balance');
            case 4:
                return __('pay_by_cash');
            case 5:
                return __('cancel_order_penalty');
        }
    }
}
