<?php

namespace App\Notifications\UserNotifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordFormLinkNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private string $resetFormLink;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $resetFormLink)
    {
        $this->resetFormLink = $resetFormLink;
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
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line("Dear " . $notifiable->name)
                    ->line("Based On Your Password Reset Request : ")
                    ->line('By This Link You Will Be Able To Reset Your Password')
                    ->action("Form 's Link", $this->resetFormLink);
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
