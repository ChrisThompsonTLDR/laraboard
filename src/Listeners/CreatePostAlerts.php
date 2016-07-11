<?php

namespace Christhompsontldr\Laraboard\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;

use Christhompsontldr\Laraboard\Events\PostCreated;
use Christhompsontldr\Laraboard\Models\Reply;
use Christhompsontldr\Laraboard\Models\Subscription;
use Christhompsontldr\Laraboard\Models\Alert;

class CreatePostAlerts implements ShouldQueue
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
    public function handle(PostCreated $event)
    {
        $subs = Subscription::where('post_id', $event->post->parent_id)->get();

        foreach ($subs as $sub) {
            Alert::updateOrCreate([
                'user_id' => $sub->user_id,
                'post_id' => $event->post->parent_id,
            ],
            [
                'user_id' => $sub->user_id,
                'post_id' => $event->post->parent_id,
            ]);
        }
    }
}