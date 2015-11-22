<?php
namespace Nodes\Translate;

use Nodes\Translate\ProviderInterface as TranslateProviderInterface;

/**
 * Class Manager
 *
 * @package Nodes\Translate
 */
class Manager
{
    /**
     * Translate provider
     * @var \Nodes\Translate\ProviderInterface
     */
    protected $provider;

    /**
     * Constructor
     *
     * @access public
     * @param  \Nodes\Translate\ProviderInterface $provider
     */
    public function __construct(TranslateProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Retrieve translated string
     *
     * @author Morten Rugaard <moru@nodes.dk>
     * @date   13-10-2015
     *
     * @access public
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
}