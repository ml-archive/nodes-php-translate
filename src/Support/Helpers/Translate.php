<?php

if (! function_exists('translate')) {
    /**
     * Translate key.
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     * @param string      $key
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
     * Translate key for the passed app.
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     * @param string      $application
     * @param string      $key
     * @param array       $replacements
     * @param string|null $locale
     * @param string|null $platform
     * @return string
     */
    function translate_app($application, $key, $replacements = [], $locale = null, $platform = null)
    {
        return app('nodes.translate')->setApplication($application)->get($key, $replacements, $locale, $platform);
    }
}
