<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use gateway\api\middleware\CorsMiddleware;

$containerBuilder = new ContainerBuilder();
$containerBuilder->useAutowiring(false);

$containerBuilder->addDefinitions(require __DIR__ . '/settings.php');
$containerBuilder->addDefinitions(require __DIR__ . '/api.php');

$container = $containerBuilder->build();

AppFactory::setContainer($container);
$app = AppFactory::create();

$app->add(new CorsMiddleware());

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

$app = (require __DIR__ . '/../src/api/routeGateway.php')($app);

return $app;
