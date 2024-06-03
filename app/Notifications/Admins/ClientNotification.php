<?php

namespace App\Notifications\Admins;

use App\Models\Client;
use BeyondCode\LaravelWebSockets\WebSockets\Channels\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ClientNotification extends Notification implements ShouldBroadcastNow 
{
    use Queueable,InteractsWithSockets;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
	protected string $title_en ;
	protected string $title_ar ;
	protected string $message_en ;
	protected string $message_ar ;
	protected string $type ;
	protected ?int $model_id ;
	protected string $createdAtFormatted ;
	protected Client $client ;
    public function __construct(Client $client , string $title_en,string $title_ar,string $message_en,string $message_ar , string $createdAtFormatted , string $type , ?int $model_id = null)
    {
		$this->client = $client ; 
        $this->title_en = $title_en ;
        $this->title_ar = $title_ar ;
        $this->message_en = $message_en ;
        $this->message_ar = $message_ar ;
		/**
		 * * // نوع الاشعار لتحديد الايكونة المناسب وليكن مثلا اشعار تحذير او خصم الخ
		 */
        $this->type = $type ; 
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
			'database','broadcast'
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
			'type'=>$this->type,
			'model_id'=>$this->model_id,
			'createdAtFormatted'=>$this->createdAtFormatted
        ];
    }
	public function toBroadcast(object $notifiable): BroadcastMessage
	{
		return new BroadcastMessage([
			'message_en'=>$this->message_en,
			'message_ar'=>$this->message_ar,
			'title_en'=>$this->title_en ,
			'title_ar'=>$this->title_ar ,
			'createdAtFormatted'=>$this->createdAtFormatted ,
		]);
	}
	public function broadcastOn()
	{
		return new PrivateChannel('client.notifications.'.$this->client->id );
	}

}
