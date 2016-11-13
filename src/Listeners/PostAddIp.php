<?php

namespace Christhompsontldr\Laraboard\Listeners;

use Christhompsontldr\Laraboard\Events\PostSaving;
use Christhompsontldr\Laraboard\Models\Post;

class PostAddIp
{
    /**
     * Adds the user's IP to the post record.
     *
     * @param PostSaving $event
     * @return void
     */
    public function handle(PostSaving $event)
    {
        //  add ip
        if (!isset($event->post->ip)) {
            $event->post->ip = request()->ip();
        }
    }
}