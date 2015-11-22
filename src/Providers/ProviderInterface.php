<?php
namespace Nodes\Translate;

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
     *
     * @access public
     * @param  string $key
     * @param  array  $replacements
     * @param  string $locale
     * @param  string $platform
     * @return string
     * @throws \Nodes\Translate\Exception\TranslateInvalidKeyException
     */
    public function get($key, $replacements = [], $locale = null, $platform = null);
}