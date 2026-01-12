<?php
namespace toubilib\core\application\ports\api;

class PatientDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $nom,
        public readonly string $prenom,
        public readonly string $date_naissance,
        public readonly string $adresse,
        public readonly int $code_postal,
        public readonly string $ville,
        public readonly string $email,
        public string $telephone
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'date_naissance' => $this->date_naissance,
            'adresse' => $this->adresse,
            'code_postal' => $this->code_postal,
            'ville' => $this->ville,
            'email' => $this->email,
            'telephone' => $this->telephone
        ];
    }
}
