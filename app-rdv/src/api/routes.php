<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use toubilib\api\actions\CreerRendezVousAction;
use toubilib\api\actions\GetPatientById;
use toubilib\api\actions\GetRdvById;
use toubilib\api\actions\HonorerRDVAction;
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

    //Route pour liser le rdv correspondant à l'id rentré
    $app->get('/rdvs/{id}', GetRdvById::class)->setName("rdvs.id");

    // Route pour lister les créneaux occupés d'un praticien sur une période
    $app->get('/praticiens/{id}/rdvs', ListerCreneauxOccupesAction::class)->setName("praticien.crenaux");

    // Agenda praticien
    $app->get('/praticiens/{id}/agenda', AgendaPraticienAction::class)->setName("praticien.aganda");

    // Annulation de rendez-vous
    $app->get('/rdvs/{id}/annuler', AnnulerRDVAction::class)->setName("rdvs");

    // Changer le statut d'un rendez-vous honorer
    $app->get('/rdvs/{id}/honorer', HonorerRDVAction::class)->setName("rdvs.honorer");

    // Changer le statut d'un rendez-vous non honorer
    $app->get('/rdvs/{id}/non_honorer', NonHonorerRDVAction::class)->setName("rdvs.non.honorer");

    // Route pour obtenir un patient selon un id
    $app->get('/patients/{id}', GetPatientById::class)->setName("patients.id");

    //Route pour ajouter un Rendez-Vous
    $app->post('/rdvs', CreerRendezVousAction::class)->add(ValidateRDVMiddleware::class)->setName("rdv.ajout");

    // Auth
    $app->post('/signin', SigninAction::class)->setName('signin');

    // Route pour récupérer l'historique des consultations d'un patient
    $app->get('/patients/{id}/consultations',HistoriquePatientAction::class)->setName('patients.consultations');;


    return $app;
};