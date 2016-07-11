<?php

namespace Christhompsontldr\Laraboard\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;

use Christhompsontldr\Laraboard\Events\PostCreated;
use Christhompsontldr\Laraboard\Models\Post;

class CreatePostSlug implements ShouldQueue
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
        //  auto slug threads and replies
        if (!isset($event->post->slug) && in_array($event->post->type, ['Reply', 'Thread', 'Board'])) {
            $found = 0;
            while($found < 1) {
                $slug = strtolower((str_random(6)));

                $found = Post::whereSlug($slug)->count();

                if ($found == 0) {
                    $event->post->slug = $slug;
                    $event->post->save();

                    $found = 1;
                }
            }
        }
    }
}