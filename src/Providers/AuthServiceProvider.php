<?php

namespace Christhompsontldr\Laraboard\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //  admins are gods
        Gate::before(function ($user, $ability) {
            //  if no Laratrust role is configured, nobody is admin
            if (!is_string(config('laraboard.user.admin_role'))) {
                return false;
            }

            //  ignore for these abilities
            if (!in_array($ability, ['laraboard::thread-subscribe','laraboard::thread-unsubscribe'])) {
                if (!is_null($user) && $user->hasRole(config('laraboard.user.admin_role'))) {
                    return true;
                }
            }
        });

        //  reply edit
        Gate::define('laraboard::reply-edit', function ($user, $post) {
            if ($post->status != 'Open') { return false; }

            return $user->id === $post->user_id;
        });

        //  reply delete
        Gate::define('laraboard::post-delete', function ($user, $post) {
            if ($post->status != 'Open') { return false; }

            return $user->id === $post->user_id;
        });

        //  thread-reply
        Gate::define('laraboard::thread-reply', function ($user, $post) {
            if (!$post->is_open) { return false; }

            return \Auth::check();
        });

        //  thread-subscribe
        Gate::define('laraboard::thread-subscribe', function ($user, $thread) {
            if (\Auth::check()) {
                //  only if they aren't already subscribed
                if (!$user->forumSubscriptions->contains('post_id', $thread->id)) {
                    return true;
                }
            }
        });

        //  thread-unsubscribe
        Gate::define('laraboard::thread-unsubscribe', function ($user, $thread) {
            if (\Auth::check()) {
                //  only if they aren't already subscribed
                if ($user->forumSubscriptions->contains('post_id', $thread->id)) {
                    return true;
                }
            }
        });

        //  thread-create
        Gate::define('laraboard::thread-create', function ($user, $board) {
            if ($board->status != 'Open') { return false; }

            return \Auth::check();
        });

        //  category-create
        Gate::define('laraboard::category-manage', function ($user) {
            //  only admins
            return false;
        });

        //  board-create
        Gate::define('laraboard::board-create', function ($user, $board) {
            if ($board->status != 'Open') { return false; }

//            return \Auth::check();
        });

        //  board-edit
        Gate::define('laraboard::board-edit', function ($user, $board) {
            return false;
        });

        //  forum-create
        Gate::define('laraboard::forum-create', function ($user) {
            return false;
        });

        //  forum-edit
        Gate::define('laraboard::forum-edit', function ($user, $category) {
            return false;
        });

        Gate::define('laraboard::post-edit', function ($user, $post) {
            if (!in_array($post->type, ['Post','Thread'])) {
                return false;
            }
            if ($user->id == $post->user_id) {
                return true;
            }
        });
    }
}
