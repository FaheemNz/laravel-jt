<?php

namespace App\Notifications;

use App\Events\PusherEvent;
use App\Order;
use App\User;
use App\Utills\Constants\FilePaths;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderNotification extends Notification
{
    use Queueable;

    private Order $order;
    private string $type;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order, string $type)
    {
        $this->order = $order;
        $this->type  = $type;
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
            'id'                =>  $this->order->id,
            'order_id'          =>  $this->order->id,
            'order_status'      =>  $this->order->status,
            'title'             =>  Order::getMessages($this->type),
            'description'       =>  '',
            'type'              =>  'order',
            'is_customer'       =>  $this->order->user_id == $notifiable->id,
            'order_img'         =>  Order::getOrderImageUrl($this->order->thumbnail),
            'profile_img'       =>  User::getProfileImageUrl()
        ];

        event(new PusherEvent($data, $notifiable->id));

        return $data;
    }
}
