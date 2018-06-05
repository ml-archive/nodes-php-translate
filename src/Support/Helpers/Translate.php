<?php

if (!function_exists('translate')) {
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
}

if (!function_exists('translate_app')) {
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

if (!function_exists('translate_or_fail')) {
    /**
     * Translate key.
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     * @param string      $key
     * @param array       $replacements
     * @param string|null $locale
     * @param string|null $platform
     * @throws \Nodes\Translate\Exception\TranslationWasNotFoundException
     * @return string
     */
    function translate_or_fail($key, array $replacements = [], $locale = null, $platform = null)
    {
        return app('nodes.translate')->getOrFail($key, $replacements, $locale, $platform);
    }
}

if (!function_exists('translate_app_or_fail')) {
    /**
     * Translate key for the passed app.
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     * @param string      $application
     * @param string      $key
     * @param array       $replacements
     * @param string|null $locale
     * @param string|null $platform
     * @throws \Nodes\Translate\Exception\TranslationWasNotFoundException
     * @return string
     */
    function translate_app_or_fail($application, $key, $replacements = [], $locale = null, $platform = null)
    {
        return app('nodes.translate')->setApplication($application)->getOrFail($key, $replacements, $locale, $platform);
    }
}

if (!function_exists('translate_with_fallback')) {
    /**
     * Translate key.
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     * @param string      $key
     * @param string      $fallback
     * @param array       $replacements
     * @param string|null $locale
     * @param string|null $platform
     * @return string
     */
    function translate_with_fallback($key, $fallback, array $replacements = [], $locale = null, $platform = null)
    {
        return app('nodes.translate')->getWithFallback($key, $replacements, $locale, $platform);
    }
}

if (!function_exists('translate_app_or_fail')) {
    /**
     * Translate key for the passed app.
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     * @param string      $application
     * @param string      $key
     * @param string      $fallback
     * @param array       $replacements
     * @param string|null $locale
     * @param string|null $platform
     * @return string
     */
    function translate_app_with_fallback($application, $key, $fallback, $replacements = [], $locale = null, $platform = null)
    {
        return app('nodes.translate')->setApplication($application)->getWithFallback($key, $replacements, $locale, $platform);
    }
}