<?php

namespace Christhompsontldr\Laraboard;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class LaraboardServiceprovider extends ServiceProvider{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    public function boot()
    {
        $this->loadViewsFrom(realpath(__DIR__.'/resources/views'), 'laraboard');
        $this->setupRoutes($this->app->router);
    }
    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function setupRoutes(Router $router)
    {
        $router->group(['namespace' => 'Christhompsontldr\Laraboard\Http\Controllers'], function($router)
        {
            require __DIR__.'/Http/routes.php';
        });
    }

    public function register()
    {
        $this->app->register('Christhompsontldr\Laraboard\Providers\AuthServiceProvider');
        $this->app->register('Christhompsontldr\Laraboard\Providers\EventServiceProvider');
        $this->app->register('Christhompsontldr\Laraboard\Providers\ViewServiceProvider');
    }
}