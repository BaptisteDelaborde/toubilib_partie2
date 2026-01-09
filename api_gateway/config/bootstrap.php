<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

$containerBuilder = new ContainerBuilder();
$containerBuilder->useAutowiring(false);

$containerBuilder->addDefinitions(require __DIR__ . '/settings.php');
$containerBuilder->addDefinitions(require __DIR__ . '/services.php');

$container = $containerBuilder->build();

AppFactory::setContainer($container);
$app = AppFactory::create();


$app = (require __DIR__ . '/../src/api/routeGateway.php')($app);

return $app;
