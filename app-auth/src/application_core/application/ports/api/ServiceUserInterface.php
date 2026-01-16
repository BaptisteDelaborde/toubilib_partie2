<?php

namespace toubilib\core\application\ports\api;

interface ServiceUserInterface{

    public function register(CredentialsDTO $credentials, int $role): ProfileDTO;
    public function byCredentials(CredentialsDTO $credentials): ?ProfileDTO;
}
