<?php

use Slim\App;
use Gateway\api\actions\ListePraticiensRemoteAction;

return function (App $app) {
    $app->get('/praticiens', ListePraticiensRemoteAction::class);
    return $app;
};
