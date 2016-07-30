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

        //  views
        $this->publishes([
            realpath(__DIR__ . '/resources/views/board')                   => resource_path('views/vendor/laraboard/board'),
            realpath(__DIR__ . '/resources/views/forum')                   => resource_path('views/vendor/laraboard/forum'),
            realpath(__DIR__ . '/resources/views/post')                    => resource_path('views/vendor/laraboard/post'),
            realpath(__DIR__ . '/resources/views/reply')                   => resource_path('views/vendor/laraboard/reply'),
            realpath(__DIR__ . '/resources/views/subscription')            => resource_path('views/vendor/laraboard/subscription'),
            realpath(__DIR__ . '/resources/views/thread')                  => resource_path('views/vendor/laraboard/thread'),
            realpath(__DIR__ . '/resources/views/layouts/forum.blade.php') => resource_path('views/vendor/laraboard/layouts/forum.blade.php'),
        ], 'views');

        //  config
        $this->publishes([
           realpath(dirname(__DIR__)) . '/config/laraboard.php' => config_path('laraboard.php'),
        ], 'config');

        //  migrations
        $this->publishes([
           realpath(dirname(__DIR__) . '/migrations') => database_path('migrations'),
        ], 'migrations');

        //  seeds
        $this->publishes([
           realpath(dirname(__DIR__) . '/seeds') => database_path('seeds'),
        ], 'seeds');
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
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Form',     'Collective\Html\FormFacade');
        $loader->alias('Html',     'Collective\Html\HtmlFacade');
        $loader->alias('Entrust',  'Zizaco\Entrust\EntrustFacade');
        $loader->alias('Markdown', 'DraperStudio\Parsedown\Facades\Parsedown');

        $this->app->register('Baum\Providers\BaumServiceProvider');
        $this->app->register('Zizaco\Entrust\EntrustServiceProvider');
        $this->app->register('Collective\Html\HtmlServiceProvider');
        $this->app->register('DraperStudio\Parsedown\ServiceProvider');
        $this->app->register('Christhompsontldr\Laraboard\Providers\AuthServiceProvider');
        $this->app->register('Christhompsontldr\Laraboard\Providers\EventServiceProvider');
        $this->app->register('Christhompsontldr\Laraboard\Providers\ViewServiceProvider');
    }
}