<?php

namespace toubilib\core\application\ports\spi\repositoryInterfaces;


use toubilib\core\application\ports\api\InputRendezVousDTO;
use toubilib\core\domain\entities\Rdv;

interface RdvRepositoryInterface {
    /**
     * Retourne tous les rendez-vous
     *
     * @return Rdv[]
     */
    public function findAll(): array;

    /**
     * Retourne un rendez-vous par son ID
     *
     * @param string $id
     * @return Rdv|null
     */
    public function findById(string $id): ?Rdv;
    public function findByPraticienAndPeriod(string $praticienId, string $dateDebut, string $dateFin): array;
    public function save(Rdv $rdv): void;
    public function updateStatus(string $id, int $status): void; //Rendez-vous annulé
    public function findConsultationsByPatientId(string $patientId): array;
}
