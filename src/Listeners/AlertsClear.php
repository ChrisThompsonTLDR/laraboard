<?php

namespace Christhompsontldr\Laraboard\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;

use Christhompsontldr\Laraboard\Events\ThreadViewed;
use Christhompsontldr\Laraboard\Models\Post;
use Christhompsontldr\Laraboard\Models\Alert;

class AlertsClear implements ShouldQueue
{
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
     * User is viewing a thread, see if we can mark notifcations as read.
     *
     * @param  ThreadViewed  $event
     * @return void
     */
    public function handle(ThreadViewed $event)
    {
        auth()->user()->unreadNotifications->where('data.alert.parent_id', $event->thread->id)->each(function ($item, $key) {
            $item->markAsRead();
        });
    }
}
