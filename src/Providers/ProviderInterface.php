<?php

namespace Nodes\Translate\Providers;

/**
 * Interface ProviderInterface.
 *
 * @interface
 */
interface ProviderInterface
{
    /**
     * Translate key.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     * @date   13-10-2015
     * @param  string      $key
     * @param  array       $replacements
     * @param  string|null $locale
     * @param  string|null $platform
     * @return string
     * @throws \Nodes\Translate\Exception\InvalidKeyException
     * @throws \Nodes\Translate\Exception\MissingCredentialsException
     */
    public function get($key, $replacements = [], $locale = null, $platform = null);

    /**
     * getOrFail
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     * @access public
     * @param string      $key
     * @param array       $replacements
     * @param string|null $locale
     * @param string|null $platform
     * @throws \Nodes\Translate\Exception\TranslationWasNotFoundException
     * @throws \Nodes\Translate\Exception\InvalidKeyException
     * @throws \Nodes\Translate\Exception\MissingCredentialsException
     * @return string
     */
    public function getOrFail($key, $replacements = [], $locale = null, $platform = null);

    /**
     * getWithFallback
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     * @access public
     * @param string      $key
     * @param string      $fallback
     * @param array       $replacements
     * @param string|null $locale
     * @param string|null $platform
     * @throws \Nodes\Translate\Exception\InvalidKeyException
     * @throws \Nodes\Translate\Exception\MissingCredentialsException
     * @return string
     */
    public function getWithFallback($key, $fallback, $replacements = [], $locale = null, $platform = null);

    /**
     * setApplication.
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     * @param $application
     * @return ProviderInterface
     * @throws \Nodes\Translate\Exception\MissingApplicationException
     */
    public function setApplication($application);
}
