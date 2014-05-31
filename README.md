# Phacebook (Laravel 4 Package)

**Phacebook** is a Facebook authentication solution for Laravel 4 PHP framework. It provides an easy way for authentication and some basic helper methods for making API requests and execute FQL queries.

## Features

**Current:**
- Easy authentication
- Fully customizable with configuration
- Result object casting
- Method for easy FQL query execution
- Unified interface for execute all request type
- Built on **Facebook PHP SDK Version 4**

**TODO:**
- Use Laravel SessionManager class instead of native session

**Planned:**
- Interface for all request types
- Possible FQL query builder :)

## Quick start

### Required setup

Add the following line to `composer.json` file, to the `required` section.

    "zolli/phacebook": "dev-master"

Update the project dependencies, by running this command:

    $ composer update

Add the serrvice provider to the`config/app.php` simple paste this line `'Zolli\Phacebook\PhacebookServiceProvider'`
to the end of the `$providers` array

    'providers' => array(

        'Illuminate\Foundation\Providers\ArtisanServiceProvider',
        'Illuminate\Auth\AuthServiceProvider',
        ...
        'Zolli\Phacebook\PhacebookServiceProvider',
    ),

You not need to add an alias for this package. The ServiceProvider register a `Phacebook` alias in runtime.

### Configuration

You must publish to configuration file first, simply run this comamnd:

	$ php artisan config:publish Zolli/Phacebook

The configuration file is well documented. Set the values wish you want.

## Usage

**Basic authentication:**
```php
if(Phacebook::authenticate()) {
	//Do any API call here
}
```

The `authenticate()` method return `TRUE` when user is successfuly authenticated, otherwise redirect to user to Facebook to giver permissions or access token if the user is already granted the permissions. After authentication user will be redirected to the URL specified in configuration. (On the `redirectAfterLoginUrl` key)

**Get user info:**
```php
if(Phacebook::authenticate()) {
	$userData = Phacebook::getUser();
}
```

**Execute FQL query:**
```php
if(Phacebook::authenticate()) {
	$fqlResult = Phacebook::executeFQLQuery("SELECT name FROM user WHERE uid = me()")->asArray();
}
```

**Execute a raw request:**
```php
if(Phacebook::authenticate()) {
	$fqlResult = Phacebook::makeRawRequest($path, $params, $requestType = 'GET');
}
```

## Release Notes

### Version 2.0.0
* Full refactor
* Updated for Facebook PHP SDK 4

### Version 1.0.0
* First release

## License

Phacebook is free software distributed under the terms of the MIT license
