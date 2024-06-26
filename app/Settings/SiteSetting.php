<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SiteSetting extends Settings 
{
	
	public $app_name_en;
	public $app_name_ar;

	public $logo;
	public $fav_icon;
	public $phone;
	public $email;
	public $whatsapp;
	public $app_link_on_google_play;
	public $facebook_url;
	public $youtube_url;
	public $instagram_url;
	public $twitter_url;
	public $WHATSAPP_APP_KEY;
	public $WHATSAPP_AUTH_KEY;
	public $deduction_type ;
	public $deduction_amount ;
	public $coupon_discount_percentage ;
	public $driving_range ;
	public $google_api_key ;
	public $MAIL_HOST ;
	public $MAIL_PORT ;
	public $MAIL_USERNAME ;
	public $MAIL_PASSWORD ;
	public $MAIL_ENCRYPTION ;
	public $MAIL_FROM_ADDRESS ;
	public $MAIL_FROM_NAME ;
	public $invitation_code_length ;
	
	public $app_guideline_into_en ;
	public $app_guideline_into_ar ;
	public $after_signup_message_en ;
	public $after_signup_message_ar ;
	public $app_guideline_items_en ;
	public $app_guideline_items_ar ;
	public $app_guideline_outro_en ;
	public $app_guideline_outro_ar ;

	
	public $travel_end_message_en ;
	public $travel_end_message_ar ;
	
	public $take_safety_en ;
	public $take_safety_ar ;
	
	public $select_your_route_en ;
	public $select_your_route_ar ;	
	
	public $choose_the_appropriate_offer_en ;
	public $choose_the_appropriate_offer_ar ;	
	
	public $follow_capitan_path_en ;
	public $follow_capitan_path_ar ;
	
	public function getLogo()
	{
		$filePath = App(self::class)->logo;
		return  $filePath && file_exists('storage/'.$filePath) ? asset('storage/'.$filePath):null;
	}
	public function getFavIcon()
	{
		$filePath = App(self::class)->fav_icon;
		return  $filePath && file_exists('storage/'.$filePath) ? asset('storage/'.$filePath):null;
	}

    public static function group(): string
    {
        return 'site';
    }
}
