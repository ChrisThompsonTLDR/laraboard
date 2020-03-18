<?php

namespace Christhompsontldr\Laraboard\Listeners;

use Christhompsontldr\Laraboard\Events\PostSaving;
use Christhompsontldr\Laraboard\Models\Post;
use Illuminate\Support\Str;

class PostAddSlug
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
        if (empty($event->post->slug)) {
            //  categories get slugs from their title
            if ($event->post->type == 'Category' || $event->post->type == 'Board') {
                $event->post->slug = Str::slug(trim(Str::limit($event->post->name, config('laraboard.category.slug_limit', 50))));
            }
            //  everything else gets a 6 character random slug
            else {
                /**
                * @todo this could be moved to a scheduled task, possibly create a cache key that contains 100 new slugs
                */
                $found = 0;
                while($found < 1) {
                    $slug = strtolower((str_random(6)));

                    $found = Post::whereSlug($slug)->count();

                    if ($found == 0) {
                        $event->post->slug = $slug;

                        $found = 1;
                    }
                }
            }
        }
    }
}
