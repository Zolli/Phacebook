<?php namespace Zolli\Phacebook;

use Config;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookRequestException;
use Facebook\FacebookSDKException;
use Facebook\FacebookSession;
use Facebook\GraphObject;
use Session;
use URL;
use Zolli\Phacebook\Exceptions\PhacebookException;
use Zolli\Phacebook\Helpers\LaravelSessionLoginHelper;

/**
 * Class Phacebook
 * Facebook API helper class for Laravel 4
 *
 * @package Zolli\Phacebook
 * @author Zoltan Borsos <zolli07@gmail.com>
 * @copyright 2014 Zoltan Borsos
 * @license MIT
 * @version 2.1
 */
class Phacebook {

    /**
     * @var FacebookRedirectLoginHelper
     */
    private $loginHelper = null;

    /**
     * @var FacebookSession
     */
    private $facebookSession = null;

    /**
     * Constructor
     *
     * Initialize the facebook application helper and the redirector
     */
    public function __construct() {
        $this->initFacebook();
        $this->tryGetSession();
    }

    /**
     * Start the session (not the laravel Sessionmanager class)
     * and set the redirect URL on the redirector
     *
     * @throw PhacebookException
     */
    private function initFacebook() {
        try {
            FacebookSession::setDefaultApplication(Config::get("Phacebook::applicationConfig.appId"), Config::get("Phacebook::applicationConfig.secret"));
            $this->loginHelper = new LaravelSessionLoginHelper(URL::to(Config::get("Phacebook::redirectUrl")));
        } catch(FacebookSDKException $e) {
            throw new PhacebookException($e->getMessage());
        }
    }

    /**
     * Try to get the session, and initialize a new FacebookSession class
     */
    private function tryGetSession() {
        if (Session::has('fb_token')) {
            $this->facebookSession = new FacebookSession(Session::get('fb_token'));

            try {
                if (!$this->facebookSession->validate()) {
                    $this->facebookSession = null;
                }
            } catch (Exception $e) {
                $this->facebookSession = null;
            }
        }
    }

    /**
     * Try tlo initialize FacebookSession with a providedd acces token
     *
     * @param String $accessToken Tha external accessToken
     * @throws Exceptions\PhacebookException
     */
    private function tryValidateSession($accessToken) {
        $this->facebookSession = new FacebookSession($accessToken);

        try {
            $this->facebookSession->validate();
        } catch(FacebookSDKException $e) {
            throw new PhacebookException($e->getMessage());
        }
    }

    /**
     * Handles the Facebook callback request
     */
    public function handleCallback() {
        try {
            $this->facebookSession = $this->loginHelper->getSessionFromRedirect();
            Session::put('fb_token', $this->facebookSession->getToken());

            return TRUE;
        } catch(FacebookRequestException $e) {
            throw new PhacebookException($e->getMessage());
        }
    }

    /**
     * Get the initialized Facebook session class
     *
     * @return FacebookSession
     */
    public function getSession() {
        return $this->facebookSession;
    }

    /**
     * Get the logged in user default data's
     *
     * @return GraphObject
     */
    public function getUser() {
        return (new FacebookRequest($this->facebookSession, 'GET', '/me'))->execute()->getGraphObject();
    }

    /**
     * Runs an FQL query and return its content
     *
     * @param String $query A valid FQL query
     * @param String $castAs Cast the result as a specialized GraphObject (GraphAlbum, GraphLocation, GraphUser)
     * @return GraphObject
     */
    public function executeFQLQuery($query, $castAs = NULL) {
        if($castAs === NULL) {
            $castAs = GraphObject::className();
        }

        return (new FacebookRequest($this->facebookSession, 'GET', '/fql', [
            'access_token' => $this->facebookSession->getToken(),
            'q' => $query,
        ]))->execute()->getGraphObject($castAs);
    }

    /**
     * Post a message to user wall, optional with a link
     *
     * @param $message
     * @param string $link
     * @return int The new post ID
     */
    public function postToWall($message, $link = "") {
        $response = (new FacebookRequest($this->facebookSession, 'POST', '/me/feed', [
            'message' => $message,
            'link' => $link,
        ]))->execute()->getResponse();

        return $response->getProperty('id');
    }

    /**
     * Make a raw request with unique parameters and url
     *
     * @param String $path The API endpoint
     * @param arra $params Parameters for the request
     * @param string $requestType The request HTTP verb
     *
     * @return \Facebook\FacebookResponse
     */
    public function makeRawRequest($path, $params, $requestType = 'GET') {
        return (new FacebookRequest($this->facebookSession, $requestType, $path, $params))->execute();
    }

    /**
     * Start an authentication request
     *
     * @param String $providedToken External token
     * @return bool True if user is already logged in, otherwise redirect to the login URL
     */
    public function authenticate($providedToken = null) {
        if($providedToken !== NULL) {
            $this->tryValidateSession($providedToken);
        }

        if($this->facebookSession == null) {
            $url = $this->loginHelper->getLoginUrl(Config::get('Phacebook::scopes'));

            return $url;
        } else {
            return TRUE;
        }
    }

}