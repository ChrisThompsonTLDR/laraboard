<?php

namespace Christhompsontldr\Laraboard\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Notification;

use Christhompsontldr\Laraboard\Events\PostSaved;
use Christhompsontldr\Laraboard\Models\Reply;
use Christhompsontldr\Laraboard\Models\Subscription;
use Christhompsontldr\Laraboard\Notifications\ReplyAdded;

class AlertsSend implements ShouldQueue
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
     * Handle the event.
     *
     * @param  PostCreated  $event
     * @return void
     */
    public function handle(PostSaved $event)
    {
        $subs = Subscription::where('post_id', $event->post->parent_id)
                            ->where('user_id', '!=', \Auth::id())
                            ->get();

        if ($subs->count() > 0) {
            $users = $subs->first()
                          ->user()
                          ->whereIn('id', $subs->pluck('user_id'))
                          ->get();

            if ($users->count() > 0) {
                Notification::send($users, new ReplyAdded($event->post));
            }
        }
    }
}