<?php

return [

    /**
     * Ide kerül a kapcsolt alkalmazás azonosítására szolgáló adatok
     */
    'applicationConfig' => [
        'appId' => '671607966227507',
        'secret' => '6545f6ee10d38abdabc12f917755e2f5',
    ],

    /**
     * Az URL amire visszajön a facebook callback
     */
    'redirectUrl' => '/facebook-callback',

    /**
     * Hova irányítson vissza miután sikeresen megtörtént az authentikáció
     */
    'redirectAfterLoginUrl' => '/',

    /**
     * A scopok emit elkérünk a usertől auth során
     */
    'scopes' => ['email'],

];