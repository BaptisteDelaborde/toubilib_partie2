<?php

namespace toubilib\core\application\ports\api;

class InputRendezVousDTO
{
    public function __construct(
        public string $praticien_id,
        public string $patient_id,
        public string $date_heure_debut,
        public int    $duree,
        public string $date_heure_fin,
        public string $date_creation,
        public string $motif_visite
    ) {}

    public function toArray(): array
    {
        return [
            'praticien_id' => $this->praticien_id,
            'patient_id' => $this->patient_id,
            'date_heure_debut' => $this->date_heure_debut,
            'duree' => $this->duree,
            'date_creation' => $this->date_creation,
            'date_heure_fin' => $this->date_heure_fin,
            'motif_visite' => $this->motif_visite
        ];
    }
}