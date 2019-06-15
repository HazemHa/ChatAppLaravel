<?php
namespace App\Listeners;

use App\Events\NotificationRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotificationRequestListener  implements ShouldQueue
{
     use InteractsWithQueue;

    public $queue = 'listeners';
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\NotificationRequest  $event
     * @return void
     */
    public function handle(NotificationRequest $event)
    {
        // Access the order using $event->order...
    }
}