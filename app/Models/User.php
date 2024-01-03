<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function orders(){
        return $this->hasMany(Order::class,'user_id');
    }

    public function notifications(){
        return $this->hasMany(UserNotifacation::class,'user_id');
    }

    public function file($type)
    {
        return $this->morphOne(Media::class, 'mediable')->where('type',$type)->orderBy('id','DESC')->first();
    }
    public function rates(){
        return $this->hasMany(UserRate::class,'user_id');
    }

    public function country(){
        return $this->belongsTo(Country::class,'country_id');
    }
    public function city(){
        return $this->belongsTo(Country::class,'city_id');
    }
    public function area(){
        return $this->belongsTo(Country::class,'area_id');
    }

    public function car_model_data(){
        return $this->belongsTo(CarsModel::class,'model_id');
    }

    public function transactions(){
        return $this->hasMany(Transaction::class,'user_id');
    }

    public function addition_services_list($lang){
        $addition_services = $this->addition_services;
        if (!is_array($addition_services))
            $addition_services = json_decode($this->addition_services);
        $services = AdditionService::Active()->whereIn('id',$addition_services)->get();
        $data = $services->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->api_name($this->lang) ?? '',
                'price' => (float)$item->price
            ];
        });
        return $data;
    }
    public function getRoolNameAttribute()
    {
        switch ($this->role_id) {
            case 1:
                return __('msg.user');
            case 2:
                return __('msg.driver');
        }
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getNameAttribute(){
        return $this->first_name.' '.$this->last_name;
    }

    public function getBalanceAttribute(){
       $sum = 0;
       if (isset($this->transactions) && count($this->transactions) > 0){
           $plus = $this->transactions()->where('type_id',2)->where('is_payment',1)->sum('price');
           $minus = $this->transactions()->whereIn('type_id',[3,5])->where('is_payment',1)->sum('price');
           $sum = $plus - $minus;
       }
       return (float)$sum;
    }

    public function getExpensesAttribute(){
       $sum = 0;
       if (isset($this->transactions) && count($this->transactions) > 0){
           $sum = $this->transactions()->whereIn('type_id',[1,3,4,5])->where('is_payment',1)->sum('price');
       }
       return (float)$sum;
    }
}
