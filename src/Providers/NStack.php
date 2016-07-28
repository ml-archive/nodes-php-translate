<?php

namespace Nodes\Translate\Providers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Nodes\Translate\Exception\InvalidCredentialsException;
use Nodes\Translate\Exception\InvalidKeyException;
use Nodes\Translate\Exception\MissingApplicationException;
use Nodes\Translate\Exception\UnsupportedStorageException;
use Nodes\Translate\Exception\MissingCredentialsException;

/**
 * Class NStack.
 */
class NStack implements ProviderInterface
{
    /**
     * NStack URL.
     *
     * @var string
     */
    protected $url = null;

    /**
     * All NStack credentials.
     *
     * @var array
     */
    protected $credentials = [];

    /**
     * NStack credentials.
     *
     * @var array
     */
    protected $appCredentials = [];

    /**
     * @var string
     */
    protected $application = 'default';

    /**
     * Default translate values.
     *
     * @var array
     */
    protected $defaults = [];

    /**
     * Translate data.
     *
     * @var array
     */
    protected $data = [];

    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    protected $failedCarbons;

    /**
     * @var string
     */
    protected $storage = 'cache';

    /**
     * @var array
     */
    protected $supportedStorages = [
        'cache',
        'publicFolder',
    ];

    /**
     * @var int
     */
    protected $cacheTime;

    /**
     *To avoid spamming the service, only retry the service if retry is above this count or sec below.
     *
     * @var int
     */
    protected $maxNetworkRetries = 3;

    /**
     * To avoid spamming the service, only retry the service if retry is above the count above or this sec.
     *
     * @var int
     */
    protected $retryNetworkAfterSec = 10;

    /**
     * NStack Constructor.
     */
    public function __construct()
    {
        // Set NStack URL
        $this->url = config('nodes.translate.nstack.url', null);

        // Set credentials
        $this->credentials = (array) config('nodes.translate.nstack.credentials', []);

        // Set default values
        $this->defaults = (array) config('nodes.translate.nstack.defaults', []);

        // Set application string
        $this->application = ! empty($this->defaults['application']) ? $this->defaults['application'] : $this->application;

        try {
            // Set application
            $this->setApplication($this->application);
        } catch (MissingApplicationException $e) {

            // Fallback for backwards compatibility
            $this->appCredentials = $this->credentials;
        }

        // Set storage
        $this->storage = config('nodes.translate.nstack.storage', 'cache');

        // Set cache time
        $this->cacheTime = config('nodes.translate.nstack.lifetime', 600);

        // Check if storage is supported
        if (! in_array($this->storage, $this->supportedStorages)) {
            throw new UnsupportedStorageException($this->storage.' is not supported', 500);
        }

        $this->failedCarbons = new Collection();
    }

    /**
     * Set another application then "default" to pull the translate values from.
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     * @param string $application
     * @return $this
     * @throws \Nodes\Exceptions\Exception
     */
    public function setApplication($application)
    {
        if (empty($this->credentials[$application])) {
            throw new MissingApplicationException(sprintf('Application [%s] was not in the credentials array (config.nodes.translate)', $application), 500);
        }

        $this->appCredentials = $this->credentials[$application];
        $this->application = $application;

        return $this;
    }

    /**
     * Translate key.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     * @date   13-10-2015
     * @param  string $key
     * @param  array  $replacements
     * @param  string $locale
     * @param  string $platform
     * @param  bool $clearCache
     * @return string
     * @throws \Nodes\Translate\Exception\InvalidKeyException
     */
    public function get($key, $replacements = [], $locale = null, $platform = null, $clearCache = false)
    {
        // We need to have a locale set for the data structure
        if (empty($locale) || ! is_string($locale)) {
            $locale = 'default';
        }

        // Load translations
        $translations = $this->shouldTryAgain() ? $this->loadTranslations($locale, $platform) : null;

        // If we for some reason has been unable to load translations
        // we'll - as a fallback - return the key untranslated.
        if (empty($translations)) {
            if ($this->shouldTryAgain()) {
                sleep(1);

                // Try again
                return $this->get($key, $replacements, $locale, $platform);
            } else {
                return $key;
            }
        }

        // Split key on "." to support sections
        $keyWithSection = explode('.', $key);

        if (count($keyWithSection) > 2) {
            // A key can only have one section.
            throw new InvalidKeyException(sprintf('Invalid structure of translate key: [%s]', $key), 500);
        } elseif (count($keyWithSection) == 1) {
            // No section defined.
            // Prepend "default" section.
            $keyWithSection = ['default', $key];
        }

        // Transform slugified sections to camelCase
        $keyWithSection[0] = camel_case(str_replace('-', '_', $keyWithSection[0]));

        // Translate key
        //
        // If key is not found or value is empty
        // we'll return the key untranslated instead.
        $translatedValue = $this->translateKey($keyWithSection, $locale);
        if (empty($translatedValue)) {
            // Already look up in NStack once, the key will not be found next time
            if ($clearCache) {
                return $key;
            } // Try to lookup network
            else {
                // Clear cache
                $this->clearFromStorage($locale, $platform);

                // Try again
                return $this->get($key, $replacements, $locale, $platform, true);
            }
        }

        return ! empty($replacements) ? $this->replaceVariables($translatedValue, $replacements) : $translatedValue;
    }

    /**
     * Translate key.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     * @date   13-10-2015
     * @param  array $keyWithSection
     * @return string
     */
    protected function translateKey(array $keyWithSection, $locale)
    {
        // Check if section exists within our translations
        if (! array_key_exists($keyWithSection[0], $this->data[$locale])) {
            return;
        }

        // Check if key exists within section
        if (! array_key_exists($keyWithSection[1], $this->data[$locale]->{$keyWithSection[0]})) {
            return;
        }

        // Return translated value
        return $this->data[$locale]->{$keyWithSection[0]}->{$keyWithSection[1]};
    }

    /**
     * Replace variables in translated value.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     * @date   13-10-2015
     * @param  string $string
     * @param  array  $replacements
     * @return string
     */
    protected function replaceVariables($string, array $replacements)
    {
        // Replace variables with a given value
        foreach ($replacements as $key => $value) {
            $string = str_replace('{'.$key.'}', $value, $string);
        }

        return $string;
    }

    /**
     * Load translations.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     * @date   13-10-2015
     * @param  string $locale
     * @param  string $platform
     * @return array
     */
    protected function loadTranslations($locale = 'default', $platform = null)
    {
        // We've already loaded translations
        // so we'll just return the same data again.
        if (! empty($this->data[$locale])) {
            return $this->data[$locale];
        }

        // Add fallback value to locale and platform
        $locale = ! empty($locale) ? $locale : $this->defaults['locale'];
        $platform = ! empty($platform) ? $platform : $this->defaults['platform'];

        // If our application is in debug mode
        // we want to bypass the caching of translations
        if (env('APP_DEBUG')) {
            return $this->data[$locale] = $this->request($locale, $platform);
        }

        // Retrieve translations from storage.
        //
        // If storage is empty or expired,
        // we'll request the translations from NStack
        // and re-build the cache with the received data.
        $data = $this->readFromStorage($locale, $platform);

        if (empty($data)) {
            // Request translations from NStack

            $data = $this->request($locale, $platform);
            // If we didn't receive any data
            // mark current request as failed
            if (empty($data)) {
                return;
            }

            // Store data
            $this->putToStorage($locale, $platform, $data);
        }

        // Set and return found translations
        $this->data[$locale] = $data;

        return $data;
    }

    /**
     * Retrieve and validate credentials.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     * @date   13-10-2015
     * @return array
     * @throws \Nodes\Translate\Exception\MissingCredentialsException
     */
    protected function getCredentials()
    {
        // Required credentials
        $requiredCredentials = ['appId', 'restKey'];

        // Missing credentials container
        $missingCredentials = [];

        // Check credentials
        foreach ($requiredCredentials as $credential) {
            if (! empty($this->appCredentials[$credential])) {
                continue;
            }

            // Add missing credential to contianer
            $missingCredentials[] = $credential;
        }

        // Missing credentials. Abort.
        if (! empty($missingCredentials)) {
            throw new MissingCredentialsException(sprintf('Missing credentials: %s', implode($missingCredentials, ', ')), 500);
        }

        return $this->appCredentials;
    }

    /**
     * Request translations from NStack.
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     * @param string $locale
     * @param string $platform
     * @return array|null
     * @throws \Nodes\Translate\Exception\MissingCredentialsException
     * @throws \Nodes\Translate\Exception\InvalidCredentialsException
     */
    protected function request($locale, $platform)
    {
        // Retrieve and validate credentials
        $credentials = $this->getCredentials();

        // Initiate Guzzle Client
        $client = new Client([
            'headers'         => [
                'X-Application-Id' => $credentials['appId'],
                'X-Rest-Api-Key'   => $credentials['restKey'],
                'Accept-Language'  => $locale,
            ],
            'timeout'         => 15,
            'connect_timeout' => 10,
            'verify'          => false,
        ]);

        try {
            // Send request
            $response = $client->get($this->url.sprintf('%s/keys', $platform));

            // Decode received content
            $content = (string) $response->getBody();
            $content = json_decode(trim($content));

            // Make sure we don't have a valid response
            if (empty($content->data)) {
                $this->performFail();

                return;
            }

            return $content->data;
        } catch (GuzzleException $e) {
            if ($e->getCode() == 403) {
                throw new InvalidCredentialsException(sprintf('The NStack credentials is invalid for [%s]', $this->application), 500);
            }

            $this->performFail();

            return;
        }
    }

    /**
     * readFromStorage.
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     * @param $locale
     * @param $platform
     * @return bool|mixed|string
     */
    protected function readFromStorage($locale, $platform)
    {
        switch ($this->storage) {
            case 'cache':
                return \Cache::get('nodes.translate_locale_'.$locale.'_platform_'.$platform.'_application_'.
                                   $this->application, []);
            case 'publicFolder':
                // Create path and file name
                $path = public_path('translate').DIRECTORY_SEPARATOR.$platform.DIRECTORY_SEPARATOR;
                $fileName = $locale.'.txt';

                // Try to stream
                $data = @file_get_contents($path.$fileName);

                // Fail if empty
                if (! $data) {
                    return false;
                }

                // Parse to object
                $data = json_decode($data);

                // Make sure to the key is there and data is fresh
                if (empty($data->STORED_UNIX) || (time() - $data->STORED_UNIX) > $this->cacheTime) {
                    return false;
                }

                return $data;

                break;
            default:
                return false;
        }
    }

    /**
     * @author Casper Rasmussen <cr@nodes.dk>
     * @param $locale
     * @param $platform
     * @param $data
     * @return bool|void
     */
    protected function putToStorage($locale, $platform, $data)
    {
        switch ($this->storage) {
            case 'cache':
                \Cache::put('nodes.translate_locale_'.$locale.'_platform_'.$platform.'_application_'.
                            $this->application, $data, $this->cacheTime);

                break;
            case 'publicFolder':
                // Create path and file name
                $path = public_path('translate').DIRECTORY_SEPARATOR.$platform.DIRECTORY_SEPARATOR;
                $fileName = $locale.'.txt';

                // Create folder if it does not exist
                if (! file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                $data->STORED_UNIX = time();

                // Save file
                file_put_contents($path.$fileName, json_encode($data));

                break;
            default:
                return false;
        }
    }

    /**
     * clearFromStorage.
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     * @param $locale
     * @param $platform
     * @return bool|mixed|string
     */
    protected function clearFromStorage($locale, $platform)
    {
        $platform = ! empty($platform) ? $platform : $this->defaults['platform'];

        $this->data = [];
        switch ($this->storage) {
            case 'cache':
                \Cache::forget('nodes.translate_locale_'.$locale.'_platform_'.$platform.'_application_'.
                               $this->application);
                break;
            case 'publicFolder':
                // Create path and file name
                $path = public_path('translate').DIRECTORY_SEPARATOR.$platform.DIRECTORY_SEPARATOR;
                $fileName = $locale.'.txt';

                // Delete file
                delete($path.$fileName);

                break;
            default:
                return false;
        }
    }

    /**
     * shouldTryAgain.
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     * @return bool
     */
    private function shouldTryAgain()
    {
        if ($this->failedCarbons->count() < $this->maxNetworkRetries) {
            return true;
        }

        /** @var Carbon $carbon */
        $carbon = $this->failedCarbons->first();

        if ($carbon->diffInSeconds(Carbon::now()) >= $this->retryNetworkAfterSec) {
            return true;
        }

        return false;
    }

    /**
     * performFail.
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     * @return void
     */
    private function performFail()
    {
        $this->failedCarbons->prepend(new Carbon());

        if ($this->failedCarbons->count() > 3) {
            $this->failedCarbons->pop();
        }
    }
}
