<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewRequestOfferNotification extends Notification
{
    use Queueable;


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
        return (new MailMessage)
                    ->line('You are add new request offer , you can explore details from this link')
                    ->line('Thank you for using our Service!')
                    ->line('--------------------------------')
                    ->line('قمت باضافة طلب عرض جديد ... يمكنك تصفح العروض الخاصة بك من خلال الرابط الاتي')
                    ->line('شكرا لاستخدامك مابين')
                    ->action('My Offer', url('/my-offers'));
    }

}
