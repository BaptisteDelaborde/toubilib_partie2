<?php

use DI\Container;
use GuzzleHttp\Client;
use Gateway\api\actions\ListePraticiensRemoteAction;

return [

    ListePraticiensRemoteAction::class => function (Container $c) {
        return new ListePraticiensRemoteAction(
            $c->get('settings')
        );
    },
];
