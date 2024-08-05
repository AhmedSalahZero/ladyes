<?php

namespace App\Models;

use App\Notifications\Admins\AdminNotification;
use App\Traits\Accessors\IsBaseModel;
use App\Traits\Scope\HasDefaultOrderScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
	use HasDefaultOrderScope ,   IsBaseModel;
	protected $casts = [
		'data'=>'array'
	];
	public function scopeOnlyAdminsNotifications(Builder $builder)
	{
		return $builder->where('notifiable_type','App\Models\Admin');
	}
	public function scopeOnlyAppNotifications(Builder $builder)
	{
		return $builder->whereIn('notifiable_type',['App\Models\Client','App\Models\Driver']);
	}
	public static function storeNewAdminNotification(string $titleEn , string $titleAr  , string $messageEn , string $messageAr)
	{
	
		foreach(Admin::onlyIsActive()->get() as $admin){
			if($admin->isNot(auth('admin')->user()) && $admin->hasPermissionTo('view app-notifications','admin')){
				$admin->notify(new AdminNotification($titleEn,$titleAr,$messageEn,$messageAr,formatForView(now())));
            }
		}
		
	}
	
	
	

}
