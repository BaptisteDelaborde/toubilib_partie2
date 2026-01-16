<?php
declare(strict_types=1);

use toubilib\api\actions\SigninAction;
use toubilib\api\actions\ValidateTokenAction;

return function( \Slim\App $app):\Slim\App {

    $app->post('/signin', SigninAction::class)->setName('signin');
    $app->post('/tokens/validate', ValidateTokenAction::class)->setName('tokens.validate');

    return $app;
};
