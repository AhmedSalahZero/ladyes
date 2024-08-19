<?php

namespace App\Notifications\Admins;
use App\Models\Client;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification as Notification;


class ClientNotification extends Notification implements ShouldQueue 
{
    use Queueable
	// ,InteractsWithSockets
	;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
	protected string $title_en ;
	protected string $title_ar ;
	protected string $message_en ;
	protected string $message_ar ;
	protected string $main_type ; // notification or chat
	protected string $secondary_type ; // if notification then [deposit or withdrawal , etc] , if chat then [chat]
	protected ?int $model_id ;
	protected string $createdAtFormatted ;
    public function __construct(string $title_en,string $title_ar,string $message_en,string $message_ar , string $createdAtFormatted , string $secondaryType , ?int $model_id = null , ? string $mainType = 'notification')
    {
        $this->title_en = $title_en ;
        $this->title_ar = $title_ar ;
        $this->message_en = $message_en ;
        $this->message_ar = $message_ar ;
		/**
		 * * // نوع الاشعار لتحديد الايكونة المناسب وليكن مثلا اشعار تحذير او خصم الخ
		 */
        $this->secondary_type = $secondaryType ; 
        $this->main_type = $mainType ; 
		$this->model_id = $model_id;
		$this->createdAtFormatted = $createdAtFormatted;
    }
	
	
	
	
	
	

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [
			'database',
			'firebase'
			// ,'broadcast'
		];
    }
    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

	
	/**
	 * * دي عباره عن 
	 * * custom notification channel 
	 * * انا اللي عاملها
	 * * app\Channels\FirebaseChannel.php
	 */
	public function toFirebase($notifiable)
	{
		$lang = getApiLang();
		return [
				
			'title' => $this->{'title_'.$lang},
			'body' => $this->{'message_'.$lang},
			'main_type'=>$this->main_type , // for example chat , notification
			'secondary_type'=>$this->secondary_type  // type of chat or notification for if main type is chat this also will be chat , if main type is notification then it may be [deposit, fine , etc]
		
		];
	}
	
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message_en'=>$this->message_en,
            'message_ar'=>$this->message_ar,
            'title_en'=>$this->title_en,
            'title_ar'=>$this->title_ar,
			'main_type'=>$this->main_type,
			'secondary_type'=>$this->secondary_type,
			'model_id'=>$this->model_id,
			'createdAtFormatted'=>$this->createdAtFormatted
        ];
    }
	// public function toBroadcast(object $notifiable): BroadcastMessage
	// {
	// 	return new BroadcastMessage([
	// 		'message_en'=>$this->message_en,
	// 		'message_ar'=>$this->message_ar,
	// 		'title_en'=>$this->title_en ,
	// 		'title_ar'=>$this->title_ar ,
	// 		'createdAtFormatted'=>$this->createdAtFormatted ,
	// 	]);
	// }
	// public function broadcastOn()
	// {
	// 	return new PrivateChannel('client.notifications.'.$this->client->id );
	// }

}
