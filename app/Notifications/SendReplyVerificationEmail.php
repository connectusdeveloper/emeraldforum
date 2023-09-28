<?php

namespace App\Notifications;

use App\Models\Reply;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SendReplyVerificationEmail extends Notification
{
    use Queueable;

    public function __construct(
        public Reply $reply
    ) {
    }

    public function toMail($notifiable)
    {
        $url = URL::signedRoute('verify.reply', ['reply' => $this->reply->id]);

        return (new MailMessage())
            ->subject(__('Confirm you reply'))
            ->greeting('Hello ' . $this->reply->guest_name . ',')
            ->line('Please confirm your reply by clicking the button below')
            ->action('Confirm Reply', $url)
            ->line('Your reply will be posted after confirmation.')
            ->line('Thank you!');
    }

    public function via($notifiable)
    {
        return ['mail'];
    }
}
