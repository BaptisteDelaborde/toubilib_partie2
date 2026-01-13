<?php

use DI\Container;
use GuzzleHttp\Client;
use gateway\api\actions\ListePraticiensRemoteAction;
use gateway\api\actions\DetailPraticienRemoteAction;
use gateway\api\actions\GenericGatewayAction;

return [
    'client.praticiens' => function (Container $c) {
        $settings = $c->get('settings');
        return new Client([
            'base_uri' => $settings['services']['praticiens_api'],
            'timeout' => 5.0,
        ]);
    },

    'client.rdv' => function (Container $c) {
        $settings = $c->get('settings');
        return new Client([
            'base_uri' => $settings['services']['rdv_api'],
            'timeout' => 5.0,
        ]);
    },

    'client.toubilib' => function (Container $c) {
        $settings = $c->get('settings');
        return new Client([
            'base_uri' => $settings['services']['toubilib_api'],
            'timeout' => 5.0,
        ]);
    },

    GenericGatewayAction::class => function (Container $c) {
        return new GenericGatewayAction(
            $c->get('client.praticiens'),
            $c->get('client.rdv'),
            $c->get('client.toubilib')
        );
    },

    ListePraticiensRemoteAction::class => function (Container $c) {
        return new ListePraticiensRemoteAction(
            $c->get('client.praticiens')
        );
    },

    DetailPraticienRemoteAction::class => function (Container $c) {
        return new DetailPraticienRemoteAction(
            $c->get('client.praticiens')
        );
    },
];
