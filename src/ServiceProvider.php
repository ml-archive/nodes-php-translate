<?php
namespace Nodes\Translate;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

/**
 * Class ServiceProvider
 * @author  Casper Rasmussen <cr@nodes.dk>
 *
 * @package Nodes\Translate
 */
class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Boot the service provider
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->publishGroups();
    }

    /**
     * Register the service provider
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     *
     * @access public
     * @return void
     */
    public function register()
    {
        $this->registerManager();
        $this->setupBindings();
    }

    /**
     * Register publish groups
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access protected
     * @return void
     */
    protected function publishGroups()
    {
        // Config files
        $this->publishes([
            __DIR__ . '/../config/translate.php' => config_path('nodes/translate.php'),
        ], 'config');
    }

    /**
     * Setup container binding
     *
     * @author Morten Rugaard <moru@nodes.dk>
     * @access protected
     * @return void
     */
    protected function setupBindings()
    {
        $this->app->bind(\Nodes\Translate\Manager::class, function ($app) {
            return $app['nodes.translate'];
        });
    }

    /**
     * Register Translate Manager
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     *
     * @access public
     * @return void
     */
    public function registerManager()
    {
        $this->app->singleton('nodes.translate', function ($app) {
            $provider = call_user_func(config('nodes.translate.provider'), $app);
            return new Manager($provider);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['nodes.translate'];
    }
}