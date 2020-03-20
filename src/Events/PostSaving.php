<?php

namespace Christhompsontldr\Laraboard\Events;

use Illuminate\Queue\SerializesModels;

class PostSaving
{
    use SerializesModels;

    public $post;

    public function __construct($post)
    {
        $this->post = $post;
    }
}
