<?php

namespace Christhompsontldr\Laraboard;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Gate;
use Blade;
use Config;
use Illuminate\Support\Facades\Route;
use Christhompsontldr\Laraboard\Models\Board;
use Christhompsontldr\Laraboard\Models\Category;
use Christhompsontldr\Laraboard\Models\Thread;

class LaraboardServiceProvider extends ServiceProvider
{
    protected $listen = [
        \Christhompsontldr\Laraboard\Events\PostSaving::class => [
            \Christhompsontldr\Laraboard\Listeners\PostAddSlug::class,    //  adds the slug field
            \Christhompsontldr\Laraboard\Listeners\PostAddIp::class,      //  adds the ip field
            \Christhompsontldr\Laraboard\Listeners\PostUpdatedBy::class,  //  adds the user_id for the person doing the updating
        ],
        \Christhompsontldr\Laraboard\Events\PostCreated::class => [
            \Christhompsontldr\Laraboard\Listeners\AlertsSend::class,   //  send notification to user about subscription
        ],
        \Christhompsontldr\Laraboard\Events\ThreadViewed::class => [
            \Christhompsontldr\Laraboard\Listeners\AlertsClear::class,  //  user viewed thread
        ],
    ];

    public function boot()
    {
        // publish configs
        $this->publishes([
           dirname(__DIR__) . '/config/laraboard.php' => config_path('laraboard.php'),
        ]);

        $this->loadViewsFrom(dirname(__DIR__) . '/resources/views', 'laraboard');

        $this->loadMigrationsFrom(dirname(__DIR__) . '/database/migrations');

        if (!$this->app->routesAreCached()) {
            $this->setupRoutes($this->app->router);
        }

        $this->setupGates();

        $this->setupBlades();
    }

    /**
    * Register the providers that are used
    *
    */
    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/config/laraboard.php', 'laraboard'
        );
    }

    /**
     * Define the routes for the package.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function setupRoutes(Router $router)
    {
        $router->group(['namespace' => 'Christhompsontldr\Laraboard\Http\Controllers'], function($router)
        {
            require __DIR__ . '/Http/routes.php';
        });

        Route::model('laraboardBoard', Board::class);
        Route::model('laraboardCategory', Category::class);
        Route::model('laraboardThread', Thread::class);
    }

    public function setupGates()
    {
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

    public function setupBlades()
    {
        /**
        * Build data for the sidebar block.
        */
        view()->composer('laraboard::*', function($view) {
            $view->with('messaging', Config::has('messenger'));
        });

        view()->composer('laraboard::category.show', function($view) {
            $data = $view->getData();

            $view->with('crumbs', [
                [
                    'name' => $data['category']->name,
                    'url'  => route('category.show', [$data['category']])
                ]
            ]);
        });

        view()->composer('laraboard::board.show', function($view) {
            $data = $view->getData();

            $view->with('crumbs', [
                [
                    'name' => $data['board']->category->name,
                    'url'  => route('category.show', $data['board']->category)
                ],
                [
                    'name' => $data['board']->name,
                    'url'  => route('board.show', [$data['board']->category, $data['board']])
                ],
            ]);
        });

        view()->composer('laraboard::thread.show', function($view) {
            $data = $view->getData();

            $view->with('crumbs', [
                [
                    'name' => $data['thread']->board->category->name,
                    'url'  => route('category.show', $data['thread']->board->category)
                ],
                [
                    'name' => $data['thread']->board->name,
                    'url'  => route('board.show', [$data['thread']->board->category, $data['thread']->board])
                ],
                [
                    'name' => $data['thread']->name,
                    'url'  => route('thread.show', [$data['thread']->board->category, $data['thread']->board, $data['thread'], $data['thread']])
                ],
            ]);
        });

        view()->composer('laraboard::post.edit', function($view) {
            $data = $view->getData();

            $thread = $data['post']->thread;

            $view->with('crumbs', [
                [
                    'name' => $thread->board->category->name,
                    'url'  => route('category.show', $thread->board->category)
                ],
                [
                    'name' => $thread->board->name,
                    'url'  => route('board.show', [$thread->board->category, $thread->board])
                ],
                [
                    'name' => $thread->name,
                    'url'  => route('thread.show', [$thread->board->category, $thread->board, $thread, $thread])
                ],
            ]);
        });
    }
}
