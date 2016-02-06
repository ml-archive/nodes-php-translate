<?php
namespace Nodes\Translate;

use Nodes\AbstractServiceProvider;

/**
 * Class ServiceProvider
 * @author  Casper Rasmussen <cr@nodes.dk>
 *
 * @package Nodes\Translate
 */
class ServiceProvider extends AbstractServiceProvider
{
    /**
     * Package name
     *
     * @var string
     */
    protected $package = 'translate';

    /**
     * Facades to install
     *
     * @var array
     */
    protected $facades = [
        'NodesTranslate' => \Nodes\Translate\Support\Facades\Translate::class
    ];

    /**
     * Array of configs to copy
     *
     * @var array
     */
    protected $configs = [
        'config/translate.php' => 'config/nodes/translate.php'
    ];

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var boolean
     */
    protected $defer = true;

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
        parent::register();

        $this->registerManager();
        $this->setupBindings();
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