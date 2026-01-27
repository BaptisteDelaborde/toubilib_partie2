<?php

use DI\Container;
use toubilib\core\application\ports\api\ServicePatientInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\PatientRepositoryInterface;
use toubilib\core\application\usecases\ServicePatient;


return [
    ServicePatientInterface::class => function(Container $container){
        $repository = $container->get(PatientRepositoryInterface::class);
        return new ServicePatient($repository);
    }
];
