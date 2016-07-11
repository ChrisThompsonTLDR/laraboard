<?php

namespace Christhompsontldr\Laraboard\Events;

use Christhompsontldr\Laraboard\Models\Post;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class PostCreated extends Event
{
    use SerializesModels;

    public $post;

    /**
     * Create a new event instance.
     *
     * @param  Podcast  $podcast
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }
}