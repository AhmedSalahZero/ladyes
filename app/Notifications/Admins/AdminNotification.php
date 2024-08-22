<?php

namespace App\Notifications\Admins;

use App\Models\Admin;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class AdminNotification extends Notification implements ShouldBroadcastNow
{
    use Queueable,InteractsWithSockets;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
	protected Admin $admin ;
	protected string $title_en ;
	protected string $title_ar ;
	protected string $message_en ;
	protected string $message_ar ;
	protected string $createdAtFormatted ;
    public function __construct(Admin $admin, string $title_en,string $title_ar,string $message_en,string $message_ar , string $createdAtFormatted )
    {
		$this->admin = $admin ;
        $this->title_en = $title_en ;
        $this->title_ar = $title_ar ;
        $this->message_en = $message_en ;
        $this->message_ar = $message_ar ;
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
		return new Channel('admin.notification.'.$this->admin->id );
	}
	// public function receivesBroadcastNotificationsOn()
	// {
	// 	return 'admin.receive.channel.'.$this->admin->id;
	// }
}
