<?php

use gateway\api\application\GuzzleClient;

return [
    GuzzleClient::class => function ($c) {
        $settings = $c->get('settings');

        return new GuzzleClient(
            $settings['services']['toubilib_api']
        );
    },
];

