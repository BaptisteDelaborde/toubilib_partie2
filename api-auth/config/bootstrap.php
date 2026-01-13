<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

if (file_exists(__DIR__ . '/.env')) {
    $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

$containerBuilder = new ContainerBuilder();
$containerBuilder->useAutowiring(false);
$containerBuilder->addDefinitions(require __DIR__ . '/settings.php');
$containerBuilder->addDefinitions(require __DIR__ . '/api.php');
$containerBuilder->addDefinitions(require __DIR__ . '/services.php');

$container = $containerBuilder->build();

AppFactory::setContainer($container);
$app = AppFactory::create();
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorHandler = $errorMiddleware->getDefaultErrorHandler();
$errorHandler->forceContentType('application/json');

$app = (require __DIR__ . '/../src/api/routes.php')($app);

return $app;