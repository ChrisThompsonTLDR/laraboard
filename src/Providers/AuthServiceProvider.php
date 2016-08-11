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
            //  if no Entrust role is configured, everyone can do everything
            if (!is_string(config('laraboard.user.admin_role'))) {
                return true;
            }

            //  ignore for these abilities
            if (!in_array($ability, ['laraboard::thread-subscribe','laraboard::thread-unsubscribe'])) {
                if (!is_null($user) && $user->hasRole(config('laraboard.user.admin_role'))) {
                    return true;
                }
            }
        });

        //  reply edit
        $gate->define('laraboard::reply-edit', function ($user, $post) {
            if ($post->status != 'Open') { return false; }

            return $user->id === $post->user_id;
        });

        //  reply delete
        $gate->define('laraboard::reply-delete', function ($user, $post) {
            if ($post->status != 'Open') { return false; }

            return $user->id === $post->user_id;
        });

        //  reply create
        $gate->define('laraboard::reply-storereply-store', function ($user, $post) {
            if ($post->status != 'Open') { return false; }

            return \Auth::check();
        });
        $gate->define('laraboard::reply-create', function ($user, $post) {
            if ($post->status != 'Open') { return false; }

            return \Auth::check();
        });
        $gate->define('thread-reply', function ($user, $post) {
            if ($post->status != 'Open') { return false; }

            return \Auth::check();
        });

        //  thread-subscribe
        $gate->define('laraboard::thread-subscribe', function ($user, $thread) {
            if ($thread->status != 'Open') { return false; }

            if (\Auth::check()) {
                //  only if they aren't already subscribed
                if (!$user->forumThreadSubscriptions->contains('post_id', $thread->id)) {
                    return true;
                }
            }
        });

        //  thread-unsubscribe
        $gate->define('laraboard::thread-unsubscribe', function ($user, $thread) {
            if ($thread->status != 'Open') { return false; }

            if (\Auth::check()) {
                //  only if they aren't already subscribed
                if ($user->forumThreadSubscriptions->contains('post_id', $thread->id)) {
                    return true;
                }
            }
        });

        //  thread-create
        $gate->define('laraboard::thread-create', function ($user, $board) {
            if ($board->status != 'Open') { return false; }

            return \Auth::check();
        });

        //  category-create
        $gate->define('laraboard::category-manage', function ($user) {
            //  only admins
            return false;
        });

        //  board-create
        $gate->define('laraboard::board-create', function ($user, $board) {
            if ($board->status != 'Open') { return false; }

//            return \Auth::check();
        });

        //  board-edit
        $gate->define('laraboard::board-edit', function ($user, $board) {
            return false;
        });

        //  forum-create
        $gate->define('laraboard::forum-create', function ($user) {
            return false;
        });

        //  forum-edit
        $gate->define('laraboard::forum-edit', function ($user, $category) {
            return false;
        });

        $gate->define('laraboard::post-edit', function ($user, $post) {
            if (!in_array($post->type, ['Post','Thread'])) {
                return false;
            }
            if ($user->id == $post->id) {
                return true;
            }
        });
    }
}
