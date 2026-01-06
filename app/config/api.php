<?php

use DI\Container;
use toubilib\api\actions\CreerRendezVousAction;
use toubilib\api\actions\DetailPraticienAction;
use toubilib\api\actions\GetPatientById;
use toubilib\api\actions\GetRdvById;
use toubilib\api\actions\HonorerRDVAction;
use toubilib\api\actions\ListePraticiensAction;
use toubilib\api\actions\ListerCreneauxOccupesAction;
use toubilib\api\actions\AgendaPraticienAction;
use toubilib\api\actions\NonHonorerRDVAction;
use toubilib\core\application\ports\api\ServicePatientInterface;
use toubilib\api\actions\AnnulerRDVAction;
use toubilib\core\application\ports\api\ServicePraticienInterface;
use toubilib\core\application\ports\api\ServiceRdvInterface;
use toubilib\api\actions\SigninAction;
use toubilib\core\application\ports\api\ServiceUserInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\RdvRepositoryInterface;
use toubilib\core\application\usecases\ServicePraticien;
use toubilib\core\application\usecases\ServiceRdv;
use toubilib\api\providers\AuthnProviderInterface;

return [

    // Liste des praticiens
    ListePraticiensAction::class => function ($c) {
        return new ListePraticiensAction(
            $c->get(ServicePraticienInterface::class)
        );
    },

    // Détail d’un praticien
    DetailPraticienAction::class => function ($c) {
        return new DetailPraticienAction(
            $c->get(ServicePraticienInterface::class)
        );
    },

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
