<?php

namespace toubilib\api\providers;

use toubilib\core\application\ports\api\AuthDTO;
use toubilib\core\application\ports\api\CredentialsDTO;
use toubilib\core\application\ports\api\ProfileDTO;

interface AuthnProviderInterface {
    //public function register(CredentialsDTO $credentials, int $role): ProfileDTO;
    public function signin(CredentialsDTO $credentials): array;
    //public function refresh(Token $token): AuthDTO;
    //public function getSignedInUser(Token $token): ProfileDTO;
}
