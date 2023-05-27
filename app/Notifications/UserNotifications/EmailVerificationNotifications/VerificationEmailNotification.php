<?php

namespace App\Notifications\UserNotifications\EmailVerificationNotifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class VerificationEmailNotification extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    private string $token ;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( string $token )
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
     * @return  MailMessage
     */
    public function toMail($notifiable) : MailMessage
    {
        return (new MailMessage())
                        ->subject("Verification Token For Your Email Address")
                        ->line("Dear ... " . $notifiable->name )
                        ->line('The Token Bellow Is Required When You Try To Verify Your Email In Our System')
                        ->line("Token : " . $this->token) ;
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
