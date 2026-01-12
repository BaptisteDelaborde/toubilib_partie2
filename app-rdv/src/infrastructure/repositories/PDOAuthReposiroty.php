<?php
namespace toubilib\infra\repositories;

use toubilib\core\application\ports\api\CredentialsDTO;
use toubilib\core\application\ports\spi\repositoryInterfaces\AuthRepositoryInterface;
use toubilib\core\domain\entities\User;

class PDOAuthReposiroty implements AuthRepositoryInterface
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findById(string $id): User
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return new User(
            id: $row['id'],
            email: $row['email'],
            password: $row['password'],
            role: $row['role']
        );
    }

    public function save(CredentialsDTO $dto, int $role):void {
        $passwordhash = password_hash($dto->password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO users (email, password, role) VALUES (:email, :password, :role)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':email', $dto->email);
        $stmt->bindParam(':password', $passwordhash);
        $stmt->bindParam(':role', $role);
        $stmt->execute();
    }

    public function findByEmail(string $email): ?User
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return new User(
            id: $row['id'],
            email: $row['email'],
            password: $row['password'],
            role: $row['role']
        );

    }
}