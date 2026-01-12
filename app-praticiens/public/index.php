<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

/* application boostrap */
$appli = require_once __DIR__ . '/../config/bootstrap.php';

/* CORS Middleware */
$appli->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
});

$appli->options('/{routes:.+}', function ($request, $response) {
    return $response;
});

$appli->run();
