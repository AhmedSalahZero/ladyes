<?php

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
		$this->migrator->add('site.whatsapp','email@email.com');
		
		$this->migrator->add('site.facebook_url','https://facebook.com');
		$this->migrator->add('site.twitter_url','https://twitter.com');
		$this->migrator->add('site.instagram_url','https://instagram.com');
		$this->migrator->add('site.youtube_url','"https://youtube.com');
		
		// $this->migrator->add('services.TEST_KEY','TEST_KEY_VALUE');
		// $this->migrator->encrypt('services.TEST_KEY');
		// or instead
		// $this->migrator->addEncrypted('services.TEST_KEY','TEST_KEY_VALUE');
		
		// $this->migrator->add('driver.Deduction Percentage',4);
		$this->migrator->add('site.deduction_percentage',14);
		$this->migrator->add('site.driving_range',15);
		$this->migrator->add('site.invitation_code_length',6);
		
		$this->migrator->add('site.WHATSAPP_APP_KEY', '582225ad-1a72-4f52-a12f-9b12bb63d7eb');
		$this->migrator->add('site.WHATSAPP_AUTH_KEY', 'tvwBmsZlIhzSovM6QB6KEoEnABMYE3AuFl29vtU2Fpf6P917Of');
		
        $this->migrator->add('site.MAIL_HOST', 'sender.mnjz.sa');
        $this->migrator->add('site.MAIL_PORT', '465');
        $this->migrator->add('site.MAIL_USERNAME', 'nqde@mnjz.sa');
        $this->migrator->add('site.MAIL_PASSWORD', 'LcqK~uew+_ki');
        $this->migrator->add('site.MAIL_ENCRYPTION', 'ssl');
        $this->migrator->add('site.MAIL_FROM_ADDRESS', 'nqde@mymnjz.com');
        $this->migrator->add('site.MAIL_FROM_NAME', 'mymnjz');
		
		
		
		
    }
};
