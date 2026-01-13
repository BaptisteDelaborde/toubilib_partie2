<?php

use gateway\api\actions\DetailPraticienRemoteAction;
use gateway\api\actions\GenericGatewayAction;
use Slim\App;
use Gateway\api\actions\ListePraticiensRemoteAction;
use gateway\api\actions\RegisterAction;


return function (App $app) {
    $app->get('/', function ($request, $response) {
        $response->getBody()->write(json_encode([
            'message' => 'API Gateway Toubilib',
            'endpoints' => [
                '/praticiens' => 'Liste des praticiens',
                '/praticiens/{id}' => 'Détail d\'un praticien',
                '/rdvs' => 'Rendez-vous (via API principale)',
            ]
        ], JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json');
    });
    
    $app->get('/praticiens', ListePraticiensRemoteAction::class);
    $app->get('/praticiens/{id}', DetailPraticienRemoteAction::class);
    $app->get('/register', RegisterAction::class);
    $app->get('/register', SigninAction::class);
    $app->get('/register', RefreshAction::class);
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', GenericGatewayAction::class);
    return $app;
};
