<?php

use DI\Container;
use toubilib\api\actions\DetailPraticienAction;
use toubilib\api\actions\ListePraticiensAction;
use toubilib\core\application\ports\api\ServicePraticienInterface;

return [
    // Liste des praticiens
    ListePraticiensAction::class => function ($c) {
        return new ListePraticiensAction(
            $c->get(ServicePraticienInterface::class)
        );
    },

    // Détail d'un praticien
    DetailPraticienAction::class => function ($c) {
        return new DetailPraticienAction(
            $c->get(ServicePraticienInterface::class)
        );
    },
];
