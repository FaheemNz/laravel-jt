<?php

namespace App\Notifications;

use App\CounterOffer;
use App\Events\PusherEvent;
use App\Order;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentClearedNotification extends Notification
{
    use Queueable;
    protected $order;
    protected $total;
    protected $title;

    /**
     * Create a new notification instance.
     *
     * @param Order $order
     * @param $title
     * @param $total
     */
    public function __construct(Order $order, $title, $total)
    {
        $this->order = $order;
        $this->title = $title;
        $this->total = $total;
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
            'id'            =>      $this->order->id,
            'order_id'      =>      $this->order->id,
            'offer_id'      =>      $this->order->id,
            'order_status'  =>      $this->order->status,
            'title'         =>      $this->title,
            'description'   =>      "You dues of ". $this->order->currency->symbol ." " . number_format($this->total,2) ." has been cleared.",
            'type'          =>      'Order',
            'is_customer'   =>      $this->order->user_id == $notifiable->id,
            'order_img'     =>      Order::getOrderImageUrl($this->order->thumbnail),
            'profile_img'   =>      $notifiable->avatar
        ];

        event(new PusherEvent($data, $notifiable->id));

        return $data;
    }
}
