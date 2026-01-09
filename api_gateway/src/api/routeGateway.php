<?php

use gateway\api\actions\DetailPraticienRemoteAction;
use Slim\App;
use Gateway\api\actions\ListePraticiensRemoteAction;


return function (App $app) {
    $app->get('/praticiens', ListePraticiensRemoteAction::class);
    $app->get('/praticiens/{id}', DetailPraticienRemoteAction::class);
    return $app;
};
