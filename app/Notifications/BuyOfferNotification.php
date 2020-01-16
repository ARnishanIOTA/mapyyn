<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BuyOfferNotification extends Notification
{
    use Queueable;

    public $model;
    /**
     * Create a new notification instance.
     *
     * @return vomodel
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // return (new MailMessage)
        // ->line('Payment about Offer Number '. $this->model . ' has been successfully')
        // ->line('Service Provider will be reply you within 5 day')
        // ->line('--------------------------------')
        // ->line('عملية الدفع الخاصة بالعرض رقم : '. $this->id . ' تمت بنجاح')
        // ->line('سيتم الرد على طلبك خلال 5 ايام من قبل مزود الخدمة')
        // ->action('Mapyyn', url('/'))

        return (new MailMessage)
        ->subject('تمت عملية الدفع بنجاح')
        ->markdown('emails.invoice', ['model' => $this->model]);
        ;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
