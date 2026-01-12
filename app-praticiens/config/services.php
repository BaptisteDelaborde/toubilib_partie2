<?php

use DI\Container;
use toubilib\core\application\ports\api\ServicePraticienInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface;
use toubilib\core\application\usecases\ServicePraticien;

return [
    ServicePraticienInterface::class => function (Container $container) {
        $repository = $container->get(PraticienRepositoryInterface::class);
        return new ServicePraticien($repository);
    },
];
