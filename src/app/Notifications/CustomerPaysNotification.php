<?php

namespace App\Notifications;

use App\CounterOffer;
use App\Events\PusherEvent;
use App\Order;
use App\Payment;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomerPaysNotification extends Notification
{
    use Queueable;
    protected $payment;
    protected $title;
    /**
     * Create a new notification instance.
     *
     * @param Payment $payment
     * @param $title
     */
    public function __construct(Payment $payment, $title)
    {
        $this->payment = $payment;
        $this->title = $title;
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
            'id'            =>      $this->payment->id,
            'order_id'      =>      $this->payment->order_id,
            'offer_id'      =>      $this->payment->offer_id ?: $this->payment->counterOffer->offer_id,
            'order_status'  =>      $this->payment->order->status,
            'title'         =>      $this->title,
            'description'   =>      "Brrring has just receive item price form customer",
            'type'          =>      'Order',
            'is_customer'   =>      $this->payment->order->user_id == $notifiable->id,
            'order_img'     =>      Order::getOrderImageUrl($this->payment->order->thumbnail),
            'profile_img'   =>      $this->payment->customer->avatar
        ];

        event(new PusherEvent($data, $notifiable->id));

        return $data;
    }
}
