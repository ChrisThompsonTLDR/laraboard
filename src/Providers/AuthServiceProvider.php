<?php

namespace Christhompsontldr\Laraboard\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        //  admins are gods
        $gate->before(function ($user, $ability) {
            //  ignore for these abilities
            if (!in_array($ability, ['thread-subscribe','thread-unsubscribe'])) {
                if (!is_null($user) && $user->hasRole('admin')) {
                    return true;
                }
            }
        });

        //  reply edit
        $gate->define('reply-edit', function ($user, $post) {
            if ($post->status != 'Open') { return false; }

            return $user->id === $post->user_id;
        });

        //  reply delete
        $gate->define('reply-delete', function ($user, $post) {
            if ($post->status != 'Open') { return false; }

            return $user->id === $post->user_id;
        });

        //  reply create
        $gate->define('reply-store', function ($user, $post) {
            if ($post->status != 'Open') { return false; }

            return \Auth::check();
        });
        $gate->define('reply-create', function ($user, $post) {
            if ($post->status != 'Open') { return false; }

            return \Auth::check();
        });
        $gate->define('thread-reply', function ($user, $post) {
            if ($post->status != 'Open') { return false; }

            return \Auth::check();
        });

        //  thread-subscribe
        $gate->define('thread-subscribe', function ($user, $thread) {
            if ($thread->status != 'Open') { return false; }

            if (\Auth::check()) {
                //  only if they aren't already subscribed
                if (!$user->forumThreadSubscriptions->contains('post_id', $thread->id)) {
                    return true;
                }
            }
        });

        //  thread-unsubscribe
        $gate->define('thread-unsubscribe', function ($user, $thread) {
            if ($thread->status != 'Open') { return false; }

            if (\Auth::check()) {
                //  only if they aren't already subscribed
                if ($user->forumThreadSubscriptions->contains('post_id', $thread->id)) {
                    return true;
                }
            }
        });

        //  thread-create
        $gate->define('thread-create', function ($user, $board) {
            if ($thread->status != 'Open') { return false; }

            return \Auth::check();
        });

        //  board-create
        $gate->define('board-create', function ($user, $category) {
            //  only admins
            return false;
        });

        //  thread-create
        $gate->define('board-create', function ($user, $board) {
            if ($board->status != 'Open') { return false; }

            return \Auth::check();
        });

        //  thread-create
        $gate->define('forum-create', function ($user) {
            return false;
        });

        //  board-show
/*        $gate->define('board-show', function ($user, $board) {dd('here');
            if ($thread->status != 'Open') { return false; }

            //  is it a team board
            if ($board->category->slug == 'teams') {
                if ($user->teams->where('slug', $board->slug)->count() == 0) {
                    return false;
                }
            }

            return true;
        });*/

        //  auto slug replies
/*        \Christhompsontldr\Laraboard\Models\Reply::creating(function ($reply) {
            $reply->type = 'Reply';
        });*/
    }
}
