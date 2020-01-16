<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ProviderResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * Token handler
    */
    public $token;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
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
        return (new MailMessage)
        ->line('You are receiving this email because we received a password reset request for your account.')
        ->line('If you did not request a password reset, no further action is required.')
        ->line('--------------------------------')
        ->line('تم ارسال هذا البريد لاستعادة كلمة المرور الخاصة بك.')
        ->line('في حالة لا يوجد طلب لاستعادة كلمة المرور ... تجاهل هذا البريد')
        ->action('Reset Password', url('providers/password/reset', $this->token));
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
