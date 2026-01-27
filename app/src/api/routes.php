<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use toubilib\api\actions\CreerRendezVousAction;
use toubilib\api\actions\DetailPraticienAction;
use toubilib\api\actions\GetPatientById;
use toubilib\api\actions\GetRdvById;
use toubilib\api\actions\HonorerRDVAction;
use toubilib\api\actions\ListePraticiensAction;
use toubilib\api\actions\ListerCreneauxOccupesAction;
use toubilib\api\actions\AgendaPraticienAction;
use toubilib\api\actions\AnnulerRDVAction;
use toubilib\api\actions\NonHonorerRDVAction;
use toubilib\api\middleware\AuthMiddleware;
use toubilib\api\middleware\AuthzMiddleware;
use toubilib\api\middleware\ValidateRDVMiddleware;
use toubilib\api\actions\SigninAction;
use toubilib\api\actions\HistoriquePatientAction;

return function( \Slim\App $app):\Slim\App {

    // Route pour obtenir un patient selon un id
    $app->get('/patients/{id}', GetPatientById::class)->setName("patients.id");

    // Route pour récupérer l'historique des consultations d'un patient
    $app->get('/patients/{id}/consultations',HistoriquePatientAction::class)->setName('patients.consultations');


    return $app;
};