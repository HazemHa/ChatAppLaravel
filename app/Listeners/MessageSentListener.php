<?php
namespace App\Listeners;

use App\Events\MessageSent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MessageSentListener  implements ShouldQueue
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
     * @param  \App\Events\MessageSent  $event
     * @return void
     */
    public function handle(MessageSent $event)
    {
        // Access the order using $event->order...
    }
}