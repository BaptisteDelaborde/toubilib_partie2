<?php
namespace toubilib\core\application\ports\api;

class PraticienDetailDTO
{
    public function __construct(
        public string $id,
        public readonly string $nom,
        public readonly string $prenom,
        public readonly string $specialite,
        public readonly string $email,
        public readonly string $telephone,
        public readonly string $ville,
        public readonly ?string $codePostal,
        public readonly ?string $structure,
        public readonly array $motifs,
        public readonly array $moyensPaiement
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'specialite' => $this->specialite,
            'email' => $this->email,
            'telephone' => $this->telephone,
            'ville' => $this->ville,
            'code_postal' => $this->codePostal,
            'structure' => $this->structure,
            'motifs' => $this->motifs,
            'moyens_paiement' => $this->moyensPaiement,
        ];
    }
}
