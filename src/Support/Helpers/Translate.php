<?php

if (!function_exists('translate')) {
    /**
     * translate
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     * @param             $key
     * @param array       $replacements
     * @param string|null $locale
     * @param string|null $platform
     * @return string
     */
    function translate($key, array $replacements = [], $locale = null, $platform = null)
    {
        return app('nodes.translate')->get($key, $replacements, $locale, $platform);
    }

    /**
     * translate_app
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     * @param string $application
     * @param        $key
     * @param array  $replacements
     * @param string $locale
     * @param string $platform
     * @return string
     */
    function translate_app($application, $key, $replacements = [], $locale = null, $platform = null)
    {
        return app('nodes.translate')->setApplication($application)->get($key, $replacements, $locale, $platform);
    }
}