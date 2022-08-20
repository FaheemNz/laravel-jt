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

class CounterOfferNotification extends Notification
{
    use Queueable;

    private CounterOffer $counterOffer;
    private string $type;
    private string $title;
    private string $orderImageName;
    private string $description;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(CounterOffer $counterOffer, string $type, string $orderImageName, string $title, string $description)
    {
        $this->counterOffer     = $counterOffer;
        $this->type             = $type;
        $this->title            = $title;
        $this->orderImageName   = $orderImageName;
        $this->description      = $description;
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
            'id'            =>      $this->counterOffer->id,
            'order_id'      =>      $this->counterOffer->order_id,
            'offer_id'      =>      $this->counterOffer->offer_id,
            'order_status'  =>      $this->counterOffer->order->status,
            'title'         =>      $this->title,
            'description'   =>      CounterOffer::getMappedMessages($this->type) . " " . $this->description,
            'type'          =>      'CounterOffer',
            'is_customer'   =>      $this->counterOffer->user_id == $notifiable->id,
            'order_img'     =>      Order::getOrderImageUrl($this->orderImageName),
            'profile_img'   =>      User::getProfileImageUrl()
        ];

        event(new PusherEvent($data, $notifiable->id));

        return $data;
    }
}
