<?php
namespace Nodes\Translate;

use Nodes\AbstractServiceProvider as NodesServiceProvider;

/**
 * Class ServiceProvider
 * @author  Casper Rasmussen <cr@nodes.dk>
 *
 * @package Nodes\Translate
 */
class ServiceProvider extends NodesServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = false; // @TODO

    /**
     * Register the service provider.
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     * @access public
     * @return void
     */
    public function register()
    {
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
        $this->app->bind('Nodes\Translate\Manager', function ($app) {
            return $app['nodes.translate'];
        });
    }

    /**
     * @author Casper Rasmussen <cr@nodes.dk>
     */
    public function registerManager()
    {
        $this->app->bindShared('nodes.translate', function ($app) {

            $provider = call_user_func(config('nodes.translate.provider'), $app);

            return new Manager($provider);
        });
    }
}