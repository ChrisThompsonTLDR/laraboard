<?php

namespace Christhompsontldr\Laraboard\Events;

use Christhompsontldr\Laraboard\Models\Thread;
use App\User;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class ThreadViewed extends Event
{
    use SerializesModels;

    public $thread;
    public $user;

    /**
     * Create a new event instance.
     *
     * @param  Podcast  $podcast
     * @return void
     */
    public function __construct(Thread $thread, User $user)
    {
        $this->thread = $thread;
        $this->user   = $user;
    }
}