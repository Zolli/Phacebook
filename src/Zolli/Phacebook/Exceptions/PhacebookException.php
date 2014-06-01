<?php namespace Zolli\Phacebook\Exceptions;

use Exception;

class PhacebookException extends Exception {

    public function __construct($message = "") {
        parent::__construct($message);
    }

}