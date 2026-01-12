<?php
declare(strict_types=1);

use toubilib\api\actions\DetailPraticienAction;
use toubilib\api\actions\ListePraticiensAction;

return function( \Slim\App $app):\Slim\App {

    // Route pour lister tous les praticiens
    $app->get('/praticiens', ListePraticiensAction::class)->setName("praticiens.all");

    // Route pour obtenir les détails d'un praticien par son ID
    $app->get('/praticiens/{id}', DetailPraticienAction::class)->setName("praticien.id");

    return $app;
};