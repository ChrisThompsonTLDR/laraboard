<?php

namespace Christhompsontldr\Laraboard\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Event;

use Christhompsontldr\Laraboard\Models\Post;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $defer = false;

    protected $listen = [
        'Christhompsontldr\Laraboard\Events\PostSaving' => [
            'Christhompsontldr\Laraboard\Listeners\PostAddSlug',  //  adds the slug field
            'Christhompsontldr\Laraboard\Listeners\PostAddIp',    //  adds the ip field
        ],
        'Christhompsontldr\Laraboard\Events\ThreadViewed' => [
            'Christhompsontldr\Laraboard\Listeners\ClearAlerts',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //  loose wiring of a Laravel observer
        Post::saving(function ($post) {
            Event::fire(new \Christhompsontldr\Laraboard\Events\PostSaving($post));
        });
    }
}
