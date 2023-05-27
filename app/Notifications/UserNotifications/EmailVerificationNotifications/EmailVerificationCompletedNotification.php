<?php

namespace App\Notifications\UserNotifications\EmailVerificationNotifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EmailVerificationCompletedNotification extends VerifyEmail implements ShouldQueue
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
     * @return  MailMessage
     */
    public function toMail($notifiable) : MailMessage
    {
        return (new MailMessage())
                        ->subject("Email Verification Operation Completed Successfully")
                        ->line("Dear ... " . $notifiable->name )
                        ->line("Congrats , Your Email Address Is Verified Now !")
                        ->line("You Have To Connect The System Administrator To Approve Your Account .");
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
