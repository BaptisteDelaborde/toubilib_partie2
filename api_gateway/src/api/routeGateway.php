<?php

use gateway\api\actions\DetailPraticienRemoteAction;
use gateway\api\actions\GenericGatewayAction;
use Slim\App;
use gateway\api\actions\ListePraticiensRemoteAction;
use gateway\api\actions\RegisterAction;
use gateway\api\actions\RefreshAction;
use gateway\api\actions\SigninAction;
use gateway\api\middleware\ValidateTokenMiddleware;


return function (App $app) {
    $container = $app->getContainer();
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
    

    $app->post('/register', RegisterAction::class);
    $app->post('/refresh', RefreshAction::class);
    
    $validateTokenMiddleware = $container->get(ValidateTokenMiddleware::class);

    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', GenericGatewayAction::class);
    return $app;
};
