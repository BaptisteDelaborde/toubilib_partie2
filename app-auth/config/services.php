<?php

use DI\Container;
use toubilib\api\providers\AuthnProviderInterface;
use toubilib\api\providers\JWTAuthnProvider;
use toubilib\api\providers\JWTManager;
use toubilib\core\application\ports\api\ServiceUserInterface;
use toubilib\core\application\usecases\ServiceUser;

return [
    ServiceUserInterface::class => function (Container $container) {
        $authRepo = $container->get(\toubilib\core\application\ports\spi\repositoryInterfaces\AuthRepositoryInterface::class);
        return new ServiceUser($authRepo);
    },
    JWTManager::class => function() {
        $secretKey = $_ENV['JWT_SECRET'] ?? 'your-secret-key';
        return new JWTManager($secretKey, 'HS512');
    },
    AuthnProviderInterface::class => function (Container $container) {
        $JWTmanager = $container->get(JWTManager::class);
        $serviceUser = $container->get(ServiceUserInterface::class);
        return new JWTAuthnProvider($JWTmanager,$serviceUser);
    },
];
