<?php namespace Zolli\Phacebook;

use Facebook;
use Config;
use Response;
use URL;
use Redirect;
use Input;

/**
 * Class Phacebook
 *
 * @package Zolli\Phacebook
 */
class Phacebook {

    /**
     * Az inícializált facebook osztály példánya
     *
     * @var Facebook
     */
    private $facebook = null;

    /**
     * A authentikáció során megkapott accessToken
     *
     * @var string
     */
    private $accessToken = null;

    /**
     * Konstruktor
     */
    public function __construct() {
        $this->initFacebook();
    }

    /**
     * Inícializálj a Facebook osztályta a configban megadott beállításokkal
     */
    private function initFacebook() {
        $this->facebook = new Facebook(Config::get('Phacebook::applicationConfig'));
    }

    /**
     * Visszaadja az inícializált facebook osztályt
     *
     * @return Facebook|null Null ha még nincs inícializálva valamiért
     */
    public function getFacebook() {
        return $this->facebook();
    }

    public function getUser() {
        return $this->facebook->api("/me");
    }

    /**
     * Lefuttat egy FQL kérést és mindenképpen aláírja az eccessToken-el
     *
     * @param string $query A kérés amit futtatni akarunk
     * @return array
     */
    public function executeFQLQuery($query) {
        $params = [
            'method' => 'fql.query',
            'access_token' => $this->accessToken,
            'query' => $query,
        ];

        return $this->facebook->api($params);
    }

    /**
     * Elvégz az authentikációt
     */
    public function authenticate() {
        if($this->facebook->getUser() == 0) {
            $url = $this->facebook->getLoginUrl([
                "redirect_uri" => URL::to(Config::get('Phacebook::redirectUrl')),
                "scope" => implode(',', Config::get('Phacebook::scopes')),
            ]);

            //Kis heggesztés egy bug miatt ami megakadályozza a Redirector osztály megflelő használatát
            Response::make('',302)->header('Location',(string)$url)->send();
        } else {
            return TRUE;
        }
    }

    public function handleCallback() {
        $code = Input::get('code');
        $this->accessToken = $this->facebook->getAccessToken();
        $this->facebook->getUser();
        Response::make('',302)->header('Location',(string)Config::get("Phacebook::redirectAfterLoginUrl"))->send();
    }

}