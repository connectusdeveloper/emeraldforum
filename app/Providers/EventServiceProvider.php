<?php

namespace App\Providers;

use App\Events\AttachmentEvent;
use Illuminate\Auth\Events\Registered;
use App\Listeners\AttachmentEventListener;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class      => [
            SendEmailVerificationNotification::class,
        ],
        AttachmentEvent::class => [
            AttachmentEventListener::class,
        ],
    ];

    public function boot()
    {
        //
    }

    public function shouldDiscoverEvents()
    {
        return false;
    }
}
