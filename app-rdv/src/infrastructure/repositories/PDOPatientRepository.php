<?php
namespace toubilib\infra\repositories;

use toubilib\core\application\ports\spi\repositoryInterfaces\PatientRepositoryInterface;
use toubilib\core\domain\entities\patient\Patient;

class PDOPatientRepository implements PatientRepositoryInterface
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }
    public function findById(string $id): Patient
    {
        $sql = "SELECT * FROM patient WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return new Patient(
            id: $row['id'],
            nom: $row['nom'],
            prenom: $row['prenom'],
            date_naissance: $row['date_naissance'],
            adresse: $row['adresse'],
            code_postal: (int) $row['code_postal'],
            ville: $row['ville'],
            email: $row['email'],
            telephone: $row['telephone']
        );
    }
}