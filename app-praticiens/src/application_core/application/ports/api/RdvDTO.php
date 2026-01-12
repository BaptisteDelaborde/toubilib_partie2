<?php

namespace toubilib\core\application\ports\api;

class RdvDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $praticien_id,
        public readonly string $patient_id,
        public readonly string $date_heure_debut,
        public readonly int    $status,
        public readonly int    $duree,
        public readonly string $date_heure_fin,
        public readonly string $date_creation,
        public readonly string $motif_visite
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'praticien_id' => $this->praticien_id,
            'patient_id' => $this->patient_id,
            'date_heure_debut' => $this->date_heure_debut,
            'status' => $this->status,
            'duree' => $this->duree,
            'date_heure_fin' => $this->date_heure_fin,
            'date_creation' => $this->date_creation,
            'motif_visite' => $this->motif_visite
        ];
    }
}
