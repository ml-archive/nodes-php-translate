<?php

namespace Nodes\Translate;

use Nodes\Translate\Providers\ProviderInterface as TranslateProviderInterface;

/**
 * Class Manager.
 */
class Manager
{
    /**
     * Translate provider.
     *
     * @var \Nodes\Translate\Providers\ProviderInterface
     */
    protected $provider;

    /**
     * Constructor.
     *
     * @param  \Nodes\Translate\Providers\ProviderInterface $provider
     */
    public function __construct(TranslateProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Retrieve translated string.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     * @date   13-10-2015
     * @param  string $key
     * @param  array  $replacements
     * @param  string $locale
     * @param  string $platform
     * @return string
     */
    public function get($key, $replacements = [], $locale = null, $platform = null)
    {
        return $this->provider->get($key, $replacements, $locale, $platform);
    }

    /**
     * setApplication.
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     * @param $application
     * @return TranslateProviderInterface
     */
    public function setApplication($application)
    {
        return $this->provider->setApplication($application);
    }
}
