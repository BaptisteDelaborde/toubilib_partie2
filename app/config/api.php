<?php

use DI\Container;
use toubilib\api\actions\CreerRendezVousAction;
use toubilib\api\actions\DetailPraticienAction;
use toubilib\api\actions\GetPatientById;
use toubilib\api\actions\GetRdvById;
use toubilib\api\actions\HistoriquePatientAction;
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

    // Patient par ID
    GetPatientById::class =>function($c) {
        return new GetPatientById(
          $c->get(ServicePatientInterface::class)
        );
    },

    // Historique de consultation d'un patient
    HistoriquePatientAction::class => function ($c) {
        return new HistoriquePatientAction(
            $c->get(ServiceRdvInterface::class)
        );
    },
];
