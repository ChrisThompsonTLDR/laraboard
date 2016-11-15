<?php

namespace Christhompsontldr\Laraboard\Listeners;

use Christhompsontldr\Laraboard\Events\PostSaving;
use Christhompsontldr\Laraboard\Models\Post;

class PostUpdatedBy
{
    /**
     * Adds a slug to the post if it's not already set
     *
     * @param PostSaving $event
     * @return void
     */
    public function handle(PostSaving $event)
    {
        //  give a slug field to any post that doesn't have one
        if (empty($event->post->update_by)) {
            $event->post->updated_by = \Auth::id();
        }
    }
}