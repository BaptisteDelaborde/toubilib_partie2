<?php
namespace toubilib\core\application\ports\spi\repositoryInterfaces;

use toubilib\core\application\ports\api\CredentialsDTO;
use toubilib\core\domain\entities\User;
interface AuthRepositoryInterface {
    public function findById (string $id): User;
    public function save(CredentialsDTO $dto, int $role):void;
    public function findByEmail(string $email): ?User;
}
