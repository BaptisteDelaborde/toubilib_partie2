<?php

namespace toubilib\core\application\ports\api;

class RdvSlotDTO
{
    public function __construct(
        public readonly string $date_heure_debut,
        public readonly string $date_heure_fin
    ) {}

    public function toArray(): array
    {
        return [
            'date_heure_debut' => $this->date_heure_debut,
            'date_heure_fin' => $this->date_heure_fin,
        ];
    }
}


