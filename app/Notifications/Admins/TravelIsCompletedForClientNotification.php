<?php

namespace App\Notifications\Admins;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
/**
 * * هنا هنبعت اشعار سوكيت للعميل ان فية الرحلة خلصت بالتالي يقيم السواق 
 */
class TravelIsCompletedForClientNotification extends Notification implements ShouldBroadcast
{
    use Queueable,InteractsWithSockets;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
	protected array $notificationDate;
	protected int $clientId ;
    public function __construct( array $notificationData , int $clientId)
    {

       $this->notificationDate = $notificationData;
	   $this->clientId = $clientId ;
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
        return $this->notificationDate;
    }
	public function toBroadcast(object $notifiable): BroadcastMessage
	{
		return new BroadcastMessage($this->notificationDate);
	}
	public function broadcastOn()
	{
		return new Channel('client.completed.travel.notifications.'.$this->clientId );
	}
	
}
