<?php

if (!function_exists('translate')) {
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
     */
    function translate($key, $replacements = [], $locale = null, $platform = null) {
        return \NodesTranslate::get($key, $replacements, $locale, $platform);
    }
}