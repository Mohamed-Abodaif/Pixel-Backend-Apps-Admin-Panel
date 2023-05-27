<?php

namespace App\Notifications\UserNotifications\StatusNotifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActiveRegistrationNotification extends Notification implements ShouldQueue
{
    use Queueable;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(  )
    {

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
                        ->subject("Successfull Registeration To Our System")
                        ->line("Dear ... " . $notifiable->name )
                        ->line("Thanks for your registration in our system , We would like to confirm your acceptance in our team ")
                        ->line("Kindly you can use this link so as to log in with your new credentials ")
                        ->line(" Link :" . url("/users-login")) ;
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
