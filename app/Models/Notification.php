<?php

namespace App\Models;

use App\Notifications\Admins\AdminNotification;
use App\Traits\Accessors\IsBaseModel;
use App\Traits\Scope\HasDefaultOrderScope;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
	use HasDefaultOrderScope ,   IsBaseModel;
	protected $casts = [
		'data'=>'array'
	];

	public static function storeNewNotification(string $titleEn , string $titleAr  , string $messageEn , string $messageAr)
	{
		foreach(Admin::onlyIsActive()->get() as $admin){
			if($admin->isNot(auth('admin')->user()) && $admin->can('view notifications')){
				$admin->notify(new AdminNotification($titleEn,$titleAr,$messageEn,$messageAr,formatForView(now())));
            }

		}
	}

}
