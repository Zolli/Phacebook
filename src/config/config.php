<?php

return [

    /*
    |--------------------------------------------------------------------------
    | The Facebook application credentials
    |--------------------------------------------------------------------------
    |
    | Paste here the Facebook application ID and the application
    | secret key to use this class for authentication purposes
    |
    | (Note, these pre-filled credentials provides access for a test application)
    |
    */
    'applicationConfig' => [
        'appId' => '671607966227507',
        'secret' => '6545f6ee10d38abdabc12f917755e2f5',
    ],

    /*
    |--------------------------------------------------------------------------
    | Application scopes
    |--------------------------------------------------------------------------
    |
    | Here you can specify the scopes (or permissions) witch will be asking
    | from the user on first contact
    |
    */
    'scopes' => ['email'],

    /*
    |--------------------------------------------------------------------------
    | Redirect URL
    |--------------------------------------------------------------------------
    |
    | You need to add a "Website platform" to the Facebook application
    | This field must be same as "Site URL" field (without base URL) in the
    | application configuration
    |
    */
    'redirectUrl' => '/facebook-callback',

    /*
    |--------------------------------------------------------------------------
    | Application login URL
    |--------------------------------------------------------------------------
    |
    | This is the base URL. Will be redirected the user after the login
    | is successful
    |
    */
    'redirectAfterLoginUrl' => '/',

];