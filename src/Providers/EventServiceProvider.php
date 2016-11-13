<?php

namespace Christhompsontldr\Laraboard\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

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
        'Christhompsontldr\Laraboard\Events\PostSaved' => [
            'Christhompsontldr\Laraboard\Listeners\AlertsSend',   //  send notification to user about subscription
        ],
        'Christhompsontldr\Laraboard\Events\ThreadViewed' => [
            'Christhompsontldr\Laraboard\Listeners\AlertsClear',  //  user viewed thread
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //  loose wiring of a Laravel observer
        Post::saving(function ($post) {
            Event::fire(new \Christhompsontldr\Laraboard\Events\PostSaving($post));
        });

        //  loose wiring of a Laravel observer
        Post::saved(function ($post) {
            Event::fire(new \Christhompsontldr\Laraboard\Events\PostSaved($post));
        });
    }
}
