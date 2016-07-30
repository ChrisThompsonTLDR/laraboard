<?php

namespace Christhompsontldr\Laraboard\Events;

use Christhompsontldr\Laraboard\Models\Post;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class PostSaving extends Event
{
    use SerializesModels;

    public $post;
    public $request;

    public function __construct(Post $post)
    {
        $this->post    = $post;
        $this->request = request();
    }
}