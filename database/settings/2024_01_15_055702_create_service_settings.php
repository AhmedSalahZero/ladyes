<?php

use App\Enum\DeductionType;
use Spatie\LaravelSettings\Migrations\SettingsMigration;


return new class extends SettingsMigration
{
    public function up(): void
    {
		// When you want to disable the ability to update the value of a setting, you can add a lock to it:
		// $dateSettings->lock('birth_date');
		
		$this->migrator->add('site.app_name_ar','نواعم');
		$this->migrator->add('site.app_name_en','نواعم');
		$this->migrator->add('site.fav_icon','settings/qmUr18NB6FAXfH4fAcVGvYIrgKAi94aYhkwFwPP7.png');
		$this->migrator->add('site.logo','settings/Gdt2Y6SezJjqnSLbKtmRfBqix5g8l64WlCKo3E5d.png');
		
		$this->migrator->add('site.phone','123456');
		$this->migrator->add('site.email','email@email.com');
		$this->migrator->add('site.whatsapp','010000000');
		$this->migrator->add('site.app_link_on_google_play','https://google.com.eg');
		$this->migrator->add('site.facebook_url','https://facebook.com');
		$this->migrator->add('site.twitter_url','https://twitter.com');
		$this->migrator->add('site.instagram_url','https://instagram.com');
		$this->migrator->add('site.youtube_url','https://youtube.com');
		
		// $this->migrator->add('services.TEST_KEY','TEST_KEY_VALUE');
		// $this->migrator->encrypt('services.TEST_KEY');
		// or instead
		// $this->migrator->addEncrypted('services.TEST_KEY','TEST_KEY_VALUE');
		
		// $this->migrator->add('driver.Deduction Percentage',4);
		$this->migrator->add('site.deduction_type',DeductionType::PERCENTAGE);
		$this->migrator->add('site.deduction_amount',14);
		/**
		 * * هي عبارة عن نسبة الخصم اللي بنضربها في سعر الرحلة علشان نجيب سعر الكوبون اللي بيتم انشائة بعد كل رحلة ناجه
		 */
		$this->migrator->add('site.coupon_discount_percentage',10);
		$this->migrator->add('site.driving_range',15);
		$this->migrator->add('site.invitation_code_length',6);
		
		$this->migrator->add('site.WHATSAPP_APP_KEY', '582225ad-1a72-4f52-a12f-9b12bb63d7eb');
		$this->migrator->add('site.WHATSAPP_AUTH_KEY', 'tvwBmsZlIhzSovM6QB6KEoEnABMYE3AuFl29vtU2Fpf6P917Of');
		$this->migrator->add('site.google_api_key', 'AIzaSyD1pzxgf9AUfrWE2pLVQanO6Ti9a5lZDGo');
		// $this->migrator->add('site.google_api_key', 'AIzaSyBd3qijGwg3gtPWhFeCVyxINEn8vPZ1mic');
		
        $this->migrator->add('site.MAIL_HOST', 'sender.mnjz.sa');
        $this->migrator->add('site.MAIL_PORT', '465');
        $this->migrator->add('site.MAIL_USERNAME', 'nqde@mnjz.sa');
        $this->migrator->add('site.MAIL_PASSWORD', 'LcqK~uew+_ki');
        $this->migrator->add('site.MAIL_ENCRYPTION', 'ssl');
        $this->migrator->add('site.MAIL_FROM_ADDRESS', 'nqde@mymnjz.com');
        $this->migrator->add('site.MAIL_FROM_NAME', 'mymnjz');
		$this->migrator->add('site.app_guideline_into_en', 'English Guideline');
		$this->migrator->add('site.app_guideline_into_ar', 'السلامة و احترام الجميع ..نحن ملتزمون بما يلي');
		$this->migrator->add('site.app_guideline_items_en', []);
		$this->migrator->add('site.app_guideline_items_ar', ['عاملي الجميع بالطلف واحترام']);
		
		
		$this->migrator->add('site.app_guideline_outro_en', 'English Guideline');
		$this->migrator->add('site.app_guideline_outro_ar', 'يجب علي الجميع الالتزام بهذه التعليمات');
		
		
		
		$this->migrator->add('site.after_signup_message_en', 'English Message');
		$this->migrator->add('site.after_signup_message_ar', 'نحن نقوم الان بعملية التحقق من المعلومات في حال توافق المعلومات واعتمادها يمكنك البدء في استخدام تطبيق الشريك في ليدز');
		
		$this->migrator->add('site.travel_end_message_en', 'Thanks For Travel');
		$this->migrator->add('site.travel_end_message_ar', 'الحمد لله علي سلامتك .. شاركي الكود مع احبائك');
		
		$this->migrator->add('site.take_safety_en', 'Thanks For Travel');
		$this->migrator->add('site.take_safety_ar', 'هذا النص هو مثال لنص يمكن ان يستبدل في نفس المساحة');
		
		$this->migrator->add('site.select_your_route_en', 'Replaceable Text');
		$this->migrator->add('site.select_your_route_ar', 'هذا النص هو مثال لنص يمكن ان يستبدل في نفس المساحة');
		
		$this->migrator->add('site.choose_the_appropriate_offer_en', 'Replaceable Text');
		$this->migrator->add('site.choose_the_appropriate_offer_ar', 'هذا النص هو مثال لنص يمكن ان يستبدل في نفس المساحة');
		
		$this->migrator->add('site.follow_capitan_path_en', 'Replaceable Text');
		$this->migrator->add('site.follow_capitan_path_ar', 'هذا النص هو مثال لنص يمكن ان يستبدل في نفس المساحة');
		
		
    }
};
