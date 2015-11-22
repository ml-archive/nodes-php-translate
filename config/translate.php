<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Provider
    |--------------------------------------------------------------------------
    |
    | Load provider that should handle the translations
    |
    */
    'provider' => function($app) {
        // Load NStack provider
        return new \Nodes\Translate\Provider\NStack;
    },

    /*
    |--------------------------------------------------------------------------
    | NStack settings
    |--------------------------------------------------------------------------
    */
    'nstack' => [
        /*
        |--------------------------------------------------------------------------
        | Storage
        |--------------------------------------------------------------------------
        |
        | Where to store the data
        | cache, publicFolder
        |
        */
        'storage' => 'cache',
        /*
        |--------------------------------------------------------------------------
        | URL of NStack
        |--------------------------------------------------------------------------
        |
        | Your application credentials on NStack. These are required
        | to perform any kinds of actions with NStack.
        |
        */
        'url' => 'https://nstack.io/api/v1/translate/',

        /*
        |--------------------------------------------------------------------------
        | Credentials
        |--------------------------------------------------------------------------
        |
        | Your application credentials on NStack. These are required
        | to perform any kinds of actions with NStack.
        |
        */
        'credentials' => [
            'appId' => '',
            'restKey' => '',
        ],

        /*
        |--------------------------------------------------------------------------
        | Defaults
        |--------------------------------------------------------------------------
        |
        | Default values regarding translations.
        |
        */
        'defaults' => [
            'locale' => 'en-GB',
            'platform' => 'backend'
        ]
    ]
];