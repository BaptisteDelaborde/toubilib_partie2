<?php

use toubilib\api\actions\SigninAction;
use toubilib\api\actions\ValidateTokenAction;
use toubilib\api\providers\AuthnProviderInterface;
use toubilib\api\providers\JWTManager;

return [
    SigninAction::class => function($c){
        return new SigninAction(
            $c->get(AuthnProviderInterface::class)
        );
    },
    ValidateTokenAction::class => function($c){
        return new ValidateTokenAction(
            $c->get(JWTManager::class)
        );
    },
];
