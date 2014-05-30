<?php namespace Zolli\Phacebook\Facades;

use Illuminate\Support\Facades\Facade;

class Phacebook extends Facade {

    protected static function getFacadeAccessor() {
        return 'Phacebook';
    }

}