<?php

namespace toubilib\core\application\ports\api;

class CredentialsDTO{

    public function __construct(
        public  string $email,
        public  string $password
    ){}


}
