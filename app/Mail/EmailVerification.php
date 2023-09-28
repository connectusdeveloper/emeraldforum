<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailVerification extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(public $verifyUrl, protected $user)
    {
    }

    public function build()
    {
        return $this->to($this->user)->subject(__('Verify Email'))
            ->markdown('emails.verify-email', ['url' => $this->verifyUrl, 'user' => $this->user]);
    }
}
