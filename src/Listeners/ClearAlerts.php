<?php

namespace Christhompsontldr\Laraboard\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;

use Christhompsontldr\Laraboard\Events\ThreadViewed;
use Christhompsontldr\Laraboard\Models\Post;
use Christhompsontldr\Laraboard\Models\Alert;

class ClearAlerts implements ShouldQueue
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
     * @param  PodcastWasPurchased  $event
     * @return void
     */
    public function handle(ThreadViewed $event)
    {
        $alerts = Alert::where('user_id', $event->user->id)->where('post_id', $event->thread->id)->active()->get();

        foreach ($alerts as $alert) {
            $alert->read_at = \Carbon\Carbon::now();
            $alert->save();
        }
    }
}