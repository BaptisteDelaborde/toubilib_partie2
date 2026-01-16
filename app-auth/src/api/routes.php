<?php
declare(strict_types=1);

use toubilib\api\actions\SigninAction;

return function( \Slim\App $app):\Slim\App {

    $app->post('/signin', SigninAction::class)->setName('signin');
    $app->post('token/validate', ValidateTokenAction::class)->setName('token.validate');

    return $app;
};
