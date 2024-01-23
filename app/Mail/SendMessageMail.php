<?php

namespace App\Mail;

use App\Settings\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
	protected $email ;
	protected $textMessage ;
	protected $messageSubject ;
	protected $receiverEmail ;
	protected $receiverName ;
	
    public function __construct(string $receiverEmail  , string $receiverName , string $messageSubject ,string $textMessage )
    {
        $this->receiverEmail = $receiverEmail;
        $this->textMessage = $textMessage;
        $this->messageSubject = $messageSubject;
        $this->receiverName = $receiverName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.emails.message',[
			'email'=>getSetting('email'),
			'logo'=>App(SiteSetting::class)->getLogo(),
			'textMessage'=>$this->textMessage,
			'websiteName'=>getSetting('app_name_'.app()->getLocale())
		])
		->from(getSetting('MAIL_FROM_ADDRESS'),getSetting('MAIL_FROM_NAME'))
		->subject($this->messageSubject)
		->to($this->receiverEmail , $this->receiverName);
    }
}
