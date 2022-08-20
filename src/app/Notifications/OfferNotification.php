<?php

namespace App\Notifications;

use App\Events\PusherEvent;
use App\Offer;
use App\Order;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OfferNotification extends Notification
{
    use Queueable;

    private Offer $offer;
    private string $type;
    private string $imageName;
    private string $title;
    private string $description;

    public function __construct(Offer $offer, string $type, string $imageName, string $title, string $description)
    {
        $this->offer = $offer;
        $this->type  = $type;
        $this->imageName = $imageName;
        $this->title = $title;
        $this->description = $description;
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
            'id'            => $this->offer->id,
            'order_id'      => $this->offer->order_id,
            'order_status'  => $this->offer->order->status,
            'title'         => $this->title,
            'description'   => Offer::getMappedMessages($this->type) . " " . $this->description,
            'type'          => 'offer',
            'is_customer'   => $this->offer->order->user_id == $notifiable->id,
            'order_img'     => Order::getOrderImageUrl($this->imageName),
            'profile_img'   => User::getProfileImageUrl()
        ];

        event(new PusherEvent($data, $notifiable->id));
        return $data;
    }
}
