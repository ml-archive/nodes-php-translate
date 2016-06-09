<?php
namespace Nodes\Translate\Providers;

/**
 * Interface ProviderInterface
 *
 * @interface
 * @package Nodes\Translate
 */
interface ProviderInterface
{
    /**
     * Translate key
     *
     * @author Morten Rugaard <moru@nodes.dk>
     * @date   13-10-2015
     * @access public
     * @param  string $key
     * @param  array  $replacements
     * @param  string $locale
     * @param  string $platform
     * @return string
     * @throws \Nodes\Translate\Exception\InvalidKeyException
     * @throws \Nodes\Translate\Exception\MissingCredentialsException
     */
    public function get($key, $replacements = [], $locale = null, $platform = null);

    /**
     * setApplication
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     * @access public
     * @param $application
     * @return ProviderInterface
     * @throws \Nodes\Translate\Exception\MissingApplicationException
     */
    public function setApplication($application);
}