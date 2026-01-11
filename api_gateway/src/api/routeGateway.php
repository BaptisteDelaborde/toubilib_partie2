<?php

use gateway\api\actions\DetailPraticienRemoteAction;
use gateway\api\actions\GenericGatewayAction;
use Slim\App;
use Gateway\api\actions\ListePraticiensRemoteAction;


return function (App $app) {
    $app->get('/praticiens', ListePraticiensRemoteAction::class);
    $app->get('/praticiens/{id}', DetailPraticienRemoteAction::class);
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', GenericGatewayAction::class);
    return $app;
};
