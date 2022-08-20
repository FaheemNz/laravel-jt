<?php

namespace App\Notifications;

use App\Events\PusherEvent;
use App\Trip;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TripNotification extends Notification
{
    use Queueable;

    private $trip;

    public function __construct($trip)
    {
        $this->trip = $trip;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $data = [
            'id'            =>  $this->trip->id,
            'order_id'      =>  $this->trip->order_id,
            'type'          =>  'trip',
//            'is_customer'   =>  $this->trip->order && $this->trip->order->user_id == $notifiable->id,
//            'order_img'     =>  $this->trip->order? $this->trip->order->thumbnail : "/images/defaults/broken.jpg",
            'profile_img'   =>  $this->trip->user->avatar
        ];

//        event(new PusherEvent($data, $notifiable->id));
        return $data;
    }
}
