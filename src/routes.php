<?php

Route::get(Config::get('Phacebook::redirectUrl'), function() {
    $this->app['Phacebook']->handleCallback();
});