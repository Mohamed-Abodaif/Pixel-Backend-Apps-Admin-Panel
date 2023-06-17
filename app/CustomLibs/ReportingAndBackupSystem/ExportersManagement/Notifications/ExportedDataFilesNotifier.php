<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExportedDataFilesNotifier extends Notification
{
    use Queueable;
    protected string $exportedDataAssetPath;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $exportedDataAssetPath)
    {
       $this->exportedDataAssetPath = $exportedDataAssetPath;
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
                    ->subject("Exported Data You Requested")
                    ->line('Hello , You Send A Request To Export Some Date .. Here You Are .')
                    ->action('Click To Download File', $this->exportedDataAssetPath)
                    ->line('Note : You Can Download The Data Within 3 Days From Now  , After 3 Days It Will Be Removed From System Storage')
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
        return [
            //
        ];
    }
}
