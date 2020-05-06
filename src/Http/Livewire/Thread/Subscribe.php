<?php

namespace Christhompsontldr\Laraboard\Http\Livewire\Thread;

use Christhompsontldr\Laraboard\Models\Subscription;
use Livewire\Component;

class Subscribe extends Component
{
    public $thread;

    public $subscribed = false;

    public function mount($thread)
    {
        $this->thread =  $thread;

        if (auth()->check()) {
            $this->subscribed = auth()->user()->whereHas('forumSubscriptions', function ($query) {
                return  $query->where('post_id', $this->thread->id);
            })->exists();
        }
    }

    public function render()
    {
        return view('laraboard::livewire.thread.subscribe');
    }

    public function subscribe()
    {
        $this->subscribed = true;

        $subscription          = new Subscription;
        $subscription->post_id = $this->thread->id;

        auth()->user()->forumSubscriptions()->save($subscription);
    }

    public function unsubscribe()
    {
        $this->subscribed = false;

        auth()->user()->forumSubscriptions()->where('post_id', $this->thread->id)->delete();
    }
}
