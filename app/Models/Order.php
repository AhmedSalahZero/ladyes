<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }


    public function address()
    {
        return $this->belongsTo(UserAddress::class, 'address_id');
    }

    public function transaction(){
        return $this->hasOne(Transaction::class,'order_id');
    }

    public function travel_traking(){
        return $this->hasMany(TravelTraking::class,'order_id')->orderBy('id','DESC');
    }
    public function getSubTotalAttribute()
    {
        $sum = $this->price * $this->meters;

        return round($sum,2);
    }

    public function getVatPriceAttribute()
    {
        $total = $this->sub_total * $this->vat / 100;
        return round($total,2);
    }

    public function getDiscountPriceAttribute()
    {
        $total = $this->sub_total * $this->coupon_discount / 100;
        return round($total,2);
    }


    public function getTotalAttribute()
    {
        $total = ($this->sub_total + $this->vat_price + $this->penalty_price) - ($this->discount_price);
        return round($total,2);
    }

    public function getFromLocationInfoAttribute()
    {

        $language = 'ar';
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$this->from_lat,$this->from_lng&language=$language&key=AIzaSyC-goXOqVqgntbrPBGMszydKihARSWoWiQ";

        $response = Http::get($url);

        $data = $response->json();
        $locationName = '';
        if ($data['status'] === 'OK')
            $locationName = $data['results'][0]['formatted_address'];


        return $locationName;
    }

    public function getToLocationInfoAttribute()
    {

        $language = 'ar';
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$this->to_lat,$this->to_lng&language=$language&key=AIzaSyC-goXOqVqgntbrPBGMszydKihARSWoWiQ";

        $response = Http::get($url);

        $data = $response->json();
        $locationName = '';
        if ($data['status'] === 'OK')
            $locationName = $data['results'][0]['formatted_address'];


        return $locationName;
    }
    public function getTotalProfitAttribute()
    {
        return $this->total * $this->profit_rate / 100;
    }

    public function getStatusNameAttribute()
    {
        switch ($this->status_id) {
            case 1:
                return __('msg.pending');
            case 2:
                return __('msg.approved');
            case 3:
                return __('msg.on_way');
            case 4:
                return __('msg.closed');
            case 5:
                return __('msg.cancel');
        }
    }

    public function getPaymentMethodNameAttribute()
    {
        switch ($this->payment_method_id) {
            case 1:
                return __('msg.credit_card');
            case 2:
                return __('msg.apple_pay');
            case 3:
                return __('msg.mada');
            case 4:
                return __('msg.wallet');
            case 5:
                return __('msg.cash');
        }
    }

    public function getTravelTypeNameAttribute()
    {
        switch ($this->travel_type_id) {
            case 1:
                return __('msg.economic');
            case 2:
                return __('msg.project');
            case 3:
                return __('msg.class');
            case 4:
                return __('msg.family');
        }
    }
}
