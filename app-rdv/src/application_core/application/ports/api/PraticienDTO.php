<?php

namespace toubilib\core\application\ports\api;

class PraticienDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $nom,
        public readonly string $prenom,
        public readonly string $ville,
        public readonly string $email,
        public readonly string $specialite
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'ville' => $this->ville,
            'email' => $this->email,
            'specialite' => $this->specialite
        ];
    }
}
