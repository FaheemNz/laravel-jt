<?php

namespace App\Notifications;

use App\Events\PusherEvent;
use App\Message;
use App\Order;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MessageNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $message;
    public function __construct(Message $message)
    {
        $this->message = $message;
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
        $chatRoom = $this->message->chatRoom;
        $data = [
            'id'            =>      $this->message->id,
            'order_id'      =>      $chatRoom->offer->order_id,
            'offer_id'      =>      $chatRoom->offer_id,
            'order_status'  =>      $chatRoom->offer->order->status,
            'title'         =>      $notifiable->first_name . ' ' . $notifiable->last_name . " messages you on " . $chatRoom->offer->order->name,
            'description'   =>      $this->message->text,
            'type'          =>      'Message',
            'is_customer'   =>      $chatRoom->offer->order->user_id == $notifiable->id,
            'order_img'     =>      Order::getOrderImageUrl($chatRoom->offer->order->thumbnail),
            'profile_img'   =>      User::getProfileImageUrl()
        ];

        event(new PusherEvent($data, $notifiable->id));

        return $data;
    }
}
