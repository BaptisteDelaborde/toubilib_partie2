<?php

use DI\Container;
use toubilib\api\actions\CreerRendezVousAction;
use toubilib\api\actions\GetPatientById;
use toubilib\api\actions\GetRdvById;
use toubilib\api\actions\HonorerRDVAction;
use toubilib\api\actions\ListerCreneauxOccupesAction;
use toubilib\api\actions\AgendaPraticienAction;
use toubilib\api\actions\NonHonorerRDVAction;
use toubilib\core\application\ports\api\ServicePatientInterface;
use toubilib\api\actions\AnnulerRDVAction;
use toubilib\core\application\ports\api\ServiceRdvInterface;
use toubilib\api\actions\SigninAction;
use toubilib\api\providers\AuthnProviderInterface;

return [

    // Rendez-vous par ID
    GetRdvById::class => function ($c) {
        return new GetRdvById(
            $c->get(ServiceRdvInterface::class)
        );
    },

    // Créneaux occupés
    ListerCreneauxOccupesAction::class => function ($c) {
        return new ListerCreneauxOccupesAction(
            $c->get(ServiceRdvInterface::class)
        );
    },
    // Agenda praticien
    AgendaPraticienAction::class => function ($c) {
        return new AgendaPraticienAction(
            $c->get(ServiceRdvInterface::class)
        );
    },
    // Annuler un RDV
    AnnulerRDVAction::class => function ($c) {
        return new AnnulerRDVAction(
            $c->get(ServiceRdvInterface::class)
        );
    },
    // Change le statut du RDV à honorer
    HonorerRDVAction::class => function ($c) {
        return new HonorerRDVAction(
            $c->get(ServiceRdvInterface::class)
        );
    },
    // Change le statut du RDV à non honorer
    NonHonorerRDVAction::class => function ($c) {
        return new NonHonorerRDVAction(
            $c->get(ServiceRdvInterface::class)
        );
    },
    // Patient par ID
    GetPatientById::class =>function($c) {
        return new GetPatientById(
          $c->get(ServicePatientInterface::class)
        );
    },
    // Ajout d'un Rendez-Vous
    CreerRendezVousAction::class => function ($c){
        return new CreerRendezVousAction(
            $c->get(ServiceRdvInterface::class)
        );
    },

    // Signin
    SigninAction::class => function($c){
        return new SigninAction(
            $c->get(AuthnProviderInterface::class)
        );
    },
];
