<?php
namespace App\Listeners;

use App\Events\FriendRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class FriendRequestListener  implements ShouldQueue
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
     * @param  \App\Events\FriendRequest  $event
     * @return void
     */
    public function handle(FriendRequest $event)
    {
        // Access the order using $event->order...
        return response()->json(['data'=> $event]);
    }
}