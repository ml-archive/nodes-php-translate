<?php
namespace Nodes\Translate\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Translate
 *
 * @package Nodes\Translate\Support\Facades
 */
class Translate extends Facade
{
    /**
     * Get the registered name of the component
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     *
     * @access protected
     * @return string
     */
    protected static function getFacadeAccessor() { return 'nodes.translate'; }
}
