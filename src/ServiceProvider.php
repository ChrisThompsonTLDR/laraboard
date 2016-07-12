<?php

namespace Christhompsontldr\Laraboard;

use Illuminate\Routing\Router;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    public function boot()
    {
        $this->loadViewsFrom(realpath(__DIR__ . '/resources/views'), 'laraboard');
        $this->setupRoutes($this->app->router);

        //  publishers
        $this->publishes([
            realpath(__DIR__ . '/resources/views') => resource_path('views/vendor/laraboard'),
        ]);

        dd(realpath(__DIR__ . '/resources/views'));
        $this->publishes([
            __DIR__.'/path/to/config/laraboard.php' => config_path('laraboard.php'),
        ]);
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
            require __DIR__.'/Http/routes.php';
        });
    }

    /**
    * Register the providers that are used
    *
    */
    public function register()
    {
        $this->app->register('Christhompsontldr\Laraboard\Providers\AuthServiceProvider');
        $this->app->register('Christhompsontldr\Laraboard\Providers\EventServiceProvider');
        $this->app->register('Christhompsontldr\Laraboard\Providers\ViewServiceProvider');
    }
}