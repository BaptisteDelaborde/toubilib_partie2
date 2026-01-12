<?php

use DI\Container;
use toubilib\api\middleware\AuthMiddleware;
use toubilib\api\middleware\AuthzMiddleware;
use toubilib\api\providers\AuthnProviderInterface;
use toubilib\api\providers\JWTAuthnProvider;
use toubilib\api\providers\JWTManager;
use toubilib\core\application\ports\api\ServiceAuthzInterface;
use toubilib\core\application\ports\api\ServicePatientInterface;
use toubilib\core\application\ports\api\ServicePraticienInterface;
use toubilib\core\application\ports\api\ServiceRdvInterface;
use toubilib\core\application\ports\api\ServiceUserInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\AuthRepositoryInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\PatientRepositoryInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface;
use toubilib\core\application\usecases\ServiceAuthz;
use toubilib\core\application\usecases\ServicePatient;
use toubilib\core\application\usecases\ServicePraticien;
use toubilib\core\application\ports\spi\repositoryInterfaces\RdvRepositoryInterface;
use toubilib\core\application\usecases\ServiceRdv;
use toubilib\core\application\usecases\ServiceUser;

return [
    ServicePraticienInterface::class => function (Container $container) {
        $repository = $container->get(PraticienRepositoryInterface::class);
        return new ServicePraticien($repository);
    },
    ServicePatientInterface::class => function(Container $container){
        $repository = $container->get(PatientRepositoryInterface::class);
        return new ServicePatient($repository);
    },
    ServiceRdvInterface::class => function (Container $container){
        $repository = $container->get(RdvRepositoryInterface::class);
        $servicePatient = $container->get(ServicePatientInterface::class);
        $servicePatricien = $container->get(ServicePraticienInterface::class);
        return new ServiceRdv($repository, $servicePatient, $servicePatricien);
    },
    ServiceUserInterface::class => function (Container $container) {
        $authRepo = $container->get(AuthRepositoryInterface::class);
        return new ServiceUser($authRepo);
    },
    ServiceAuthzInterface::class => function (Container $container) {
        $rdvRepo = $container->get(RdvRepositoryInterface::class);
        $praticienRepo = $container->get(PraticienRepositoryInterface::class);
        return new ServiceAuthz($rdvRepo, $praticienRepo);
    },
    JWTManager::class => function() {
        $secretKey = $_ENV['JWT_SECRET'];
        return new JWTManager($secretKey, 'HS512');
    },
    AuthnProviderInterface::class => function (Container $container) {
        $JWTmanager = $container->get(JWTManager::class);
        $serviceUser = $container->get(ServiceUserInterface::class);
        return new JWTAuthnProvider($JWTmanager,$serviceUser);
    },
    ServiceAuthz::class => function (Container $container) {
        $rdvRepo = $container->get(RdvRepositoryInterface::class);
        $praticienRepo = $container->get(PraticienRepositoryInterface::class);
        return new ServiceAuthz($rdvRepo, $praticienRepo);
    },

    AuthzMiddleware::class => function (Container $container) {
        $authzService = $container->get(ServiceAuthz::class);
        return new AuthzMiddleware($authzService);
    },
    AuthMiddleware::class => function ($container) {
        $secretKey = $_ENV['JWT_SECRET'];
        return new AuthMiddleware($secretKey);
    },

];
