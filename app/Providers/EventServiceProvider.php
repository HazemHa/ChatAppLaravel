<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use App\Events\FriendRequest;
use App\Events\MessageSent;
use App\Events\NotificationRequest;

use App\Listeners\FriendRequestListener;
use App\Listeners\MessageSentListener;
use App\Listeners\NotificationRequestListener;


use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        FriendRequest::class => [
            FriendRequestListener::class,
        ],
        MessageSent::class => [
            MessageSentListener::class,
        ],
        NotificationRequest::class => [
            NotificationRequestListener::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
