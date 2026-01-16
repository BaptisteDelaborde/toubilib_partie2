<?php

use toubilib\api\actions\SigninAction;
use toubilib\api\providers\AuthnProviderInterface;

return [
    SigninAction::class => function($c){
        return new SigninAction(
            $c->get(AuthnProviderInterface::class)
        );
    },
];
