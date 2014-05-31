<?php namespace Zolli\Phacebook\Helpers;

use Facebook\FacebookRedirectLoginHelper as FacebookLoginHelper;
use Session;

class LaravelSessionLoginHelper extends FacebookLoginHelper {

    private $sessionPrefix = "FB_SS_PREFIX_";

    protected function storeState($state) {
        Session::put($this->sessionPrefix . 'state', $state);
    }

    protected function loadState() {
        if(Session::has($this->sessionPrefix . 'state')) {
            $this->state = Session::get($this->sessionPrefix . 'state');
            return $this->state;
        }
        return null;
    }

}