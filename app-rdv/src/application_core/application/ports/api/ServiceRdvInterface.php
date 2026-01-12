<?php
namespace toubilib\core\application\ports\api;

interface ServiceRdvInterface {

    /**
     * @return RdvDTO[]
     */
    public function listerRdv(): array;

    /**
     * @return RdvDTO|null
     */
    public function chercherParId(string $id): ?RdvDTO;
    public function listerCreneauxOccupes(string $praticienId, string $dateDebut, string $dateFin): array;
    public function creerRendezVous(InputRendezVousDTO $dto): RdvDTO;
    public function agendaPraticien(string $praticienId, ?string $dateDebut = null, ?string $dateFin = null): array;
    public function annulerRendezVous(string $id): RdvDTO;
    public function honorerRendezVous(string $id): RdvDTO;
    public function nonHonorerRendezVous(string $id): RdvDTO;
}
