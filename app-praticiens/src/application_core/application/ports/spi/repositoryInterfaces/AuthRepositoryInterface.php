<?php
namespace toubilib\core\application\ports\spi\repositoryInterfaces;

use toubilib\core\domain\entities\User;
interface AuthRepositoryInterface {
    public function findById (string $id): User;
}