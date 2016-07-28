<?php

namespace Nodes\Translate\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Translate.
 */
class Translate extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'nodes.translate';
    }
}
