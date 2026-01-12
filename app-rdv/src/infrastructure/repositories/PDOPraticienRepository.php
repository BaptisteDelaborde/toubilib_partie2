<?php

namespace toubilib\infra\repositories;

use PDO;
use toubilib\core\application\ports\api\PraticienDetailDTO;
use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface;
use toubilib\core\domain\entities\praticien\Praticien;
use Ramsey\Uuid\Uuid;

class PDOPraticienRepository implements PraticienRepositoryInterface
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $sql = "
            SELECT 
                p.id,
                p.nom,
                p.prenom,
                p.ville,
                p.email,
                p.telephone,
                p.specialite_id,
                p.structure_id,
                p.rpps_id,
                p.organisation,
                p.nouveau_patient,
                p.titre,
                s.libelle as specialite_libelle
            FROM praticien p
            LEFT JOIN specialite s ON p.specialite_id = s.id
            ORDER BY p.nom, p.prenom
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        
        $praticiens = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $praticiens[] = new Praticien(
                id: Uuid::fromString($row['id']),
                nom: $row['nom'],
                prenom: $row['prenom'],
                ville: $row['ville'],
                email: $row['email'],
                telephone: $row['telephone'],
                specialiteId: (int) $row['specialite_id'],
                specialiteLibelle: $row['specialite_libelle'] ?? 'Non spécifiée',
                structureId: $row['structure_id'] ? Uuid::fromString($row['structure_id']) : null,
                rppsId: $row['rpps_id'],
                organisation: (bool) $row['organisation'],
                nouveauPatient: (bool) $row['nouveau_patient'],
                titre: $row['titre']
            );
        }

        return $praticiens;
    }

    public function findById(string $id): ?Praticien
    {
        $sql = "
            SELECT 
                p.id,
                p.nom,
                p.prenom,
                p.ville,
                p.email,
                p.telephone,
                p.specialite_id,
                p.structure_id,
                p.rpps_id,
                p.organisation,
                p.nouveau_patient,
                p.titre,
                s.libelle as specialite_libelle
            FROM praticien p
            LEFT JOIN specialite s ON p.specialite_id = s.id
            WHERE p.id = :id
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return new Praticien(
            id: Uuid::fromString($row['id']),
            nom: $row['nom'],
            prenom: $row['prenom'],
            ville: $row['ville'],
            email: $row['email'],
            telephone: $row['telephone'],
            specialiteId: (int) $row['specialite_id'],
            specialiteLibelle: $row['specialite_libelle'] ?? 'Non spécifiée',
            structureId: $row['structure_id'] ? Uuid::fromString($row['structure_id']) : null,
            rppsId: $row['rpps_id'],
            organisation: (bool) $row['organisation'],
            nouveauPatient: (bool) $row['nouveau_patient'],
            titre: $row['titre']
        );
    }

    public function findDetailById(string $id): PraticienDetailDTO
    {
        $sql = "
        SELECT p.*, s.libelle AS specialite_libelle, st.nom AS structure_nom, st.code_postal
        FROM praticien p
        JOIN specialite s ON p.specialite_id = s.id
        LEFT JOIN structure st ON p.structure_id = st.id
        WHERE p.id = :id
    ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            throw new \Exception("Praticien with ID $id not found.");
        }

        // Motifs
        $motifs = $this->pdo->prepare("
        SELECT m.libelle
        FROM praticien2motif pm
        JOIN motif_visite m ON pm.motif_id = m.id
        WHERE pm.praticien_id = :id
    ");
        $motifs->execute(['id' => $id]);
        $motifsList = array_column($motifs->fetchAll(PDO::FETCH_ASSOC), 'libelle');

        // Moyens de paiement
        $moyens = $this->pdo->prepare("
        SELECT mp.libelle
        FROM praticien2moyen pm
        JOIN moyen_paiement mp ON pm.moyen_id = mp.id
        WHERE pm.praticien_id = :id
    ");
        $moyens->execute(['id' => $id]);
        $moyensList = array_column($moyens->fetchAll(PDO::FETCH_ASSOC), 'libelle');

        return new PraticienDetailDTO(
            id: $row['id'],
            nom: $row['nom'],
            prenom: $row['prenom'],
            specialite: $row['specialite_libelle'],
            email: $row['email'],
            telephone: $row['telephone'],
            ville: $row['ville'],
            codePostal: $row['code_postal'] ?? null,
            structure: $row['structure_nom'] ?? null,
            motifs: $motifsList,
            moyensPaiement: $moyensList
        );
    }

    public function findByParam(?int $specialite, ?string $ville): array
    {
        if ($specialite === null && $ville === null) {
            return $this->findAll();
        }

        if ($specialite === null && $ville !== null) {
            $sql = "
            SELECT p.*, s.libelle AS specialite_libelle
            FROM praticien p
            LEFT JOIN specialite s ON p.specialite_id = s.id
            WHERE p.ville = :ville
        ";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['ville' => $ville]);
        }

        elseif ($specialite !== null && $ville === null) {
            $sql = "
            SELECT p.*, s.libelle AS specialite_libelle
            FROM praticien p
            LEFT JOIN specialite s ON p.specialite_id = s.id
            WHERE p.specialite_id = :specialite
        ";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['specialite' => $specialite]);
        }

        else {
            $sql = "
            SELECT p.*, s.libelle AS specialite_libelle
            FROM praticien p
            LEFT JOIN specialite s ON p.specialite_id = s.id
            WHERE p.specialite_id = :specialite
              AND p.ville = :ville
        ";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'specialite' => $specialite,
                'ville' => $ville
            ]);
        }

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function($row) {
            return new Praticien(
                id: Uuid::fromString($row['id']),
                nom: $row['nom'],
                prenom: $row['prenom'],
                ville: $row['ville'],
                email: $row['email'],
                telephone: $row['telephone'],
                specialiteId: (int) $row['specialite_id'],
                specialiteLibelle: $row['specialite_libelle'] ?? 'Non spécifiée',
                structureId: $row['structure_id'] ? Uuid::fromString($row['structure_id']) : null,
                rppsId: $row['rpps_id'],
                organisation: (bool) $row['organisation'],
                nouveauPatient: (bool) $row['nouveau_patient'],
                titre: $row['titre']
            );
        }, $rows);
    }

}