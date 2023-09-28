<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public array $data
    ) {
    }

    public function toArray($notifiable)
    {
        return $this->data;
    }

    public function toMail($notifiable)
    {
        $url = URL::signedRoute('notifications', ['user' => $notifiable->id]);
        $profile = route('profile.show');

        return (new MailMessage())
            ->greeting('Hello ' . $notifiable->name . ',')
            ->subject($this->data['title'])
            ->line($this->data['text'])
            ->action('View Thread', $this->data['link'])
            ->line('<small>You can disabled email notifications by <a href="' . $url . '">clicking here</a> or from <a href="' . $profile . '">your profile</a> page.</small>');
    }

    public function via($notifiable)
    {
        return $notifiable->prefers_email ? ['mail', 'database'] : ['database'];
    }
}
