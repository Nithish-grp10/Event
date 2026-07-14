<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewApplicationNotification extends Notification
{
    use Queueable;

    public $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function via(object $notifiable): array
    {
        return ['database']; // we will just use database notifications for admins
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_application',
            'application_id' => $this->application->id,
            'event_title' => $this->application->event->title ?? 'Unknown',
            'applicant_name' => $this->application->applicant_name,
            'message' => 'New application received from ' . $this->application->applicant_name . ' for ' . ($this->application->event->title ?? 'Event'),
            'url' => route('applications.show', $this->application->id)
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }


}
