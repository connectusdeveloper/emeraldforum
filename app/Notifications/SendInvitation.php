<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SendInvitation extends Notification
{
    use Queueable;

    public function toMail(object $notifiable): MailMessage
    {
        $settings = site_config();
        $forum = $settings['name'] ?? config('app.name');
        $url = URL::signedRoute('invitations.accept', ['invitation' => $notifiable->id, 'code' => $notifiable->code]);

        return (new MailMessage())
        ->subject(__('Invitation to join :forum', ['forum' => $forum]))
            ->line(__(':User has invited you to join his favorite forum **:forum**', ['User' => $notifiable->user->name, 'forum' => $forum]))
            ->action(__('Accept Invitation'), $url)
            ->line(str(__('Your invitation acceptance code is **`:code`**', ['code' => $notifiable->code]))->markdown()->toHtmlString())
            ->line(__('Thank you!'));
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }
}
