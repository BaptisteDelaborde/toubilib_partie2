<?php

namespace toubilib\core\domain\entities\praticien;

use Ramsey\Uuid\UuidInterface;

class Praticien
{
    public function __construct(
        private UuidInterface $id,
        private string $nom,
        private string $prenom,
        private string $ville,
        private string $email,
        private string $telephone,
        private int $specialiteId,
        private string $specialiteLibelle,
        private ?UuidInterface $structureId = null,
        private ?string $rppsId = null,
        private bool $organisation = false,
        private bool $nouveauPatient = true,
        private string $titre = 'Dr.'
    ) {}

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function getVille(): string
    {
        return $this->ville;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getTelephone(): string
    {
        return $this->telephone;
    }

    public function getSpecialiteId(): int
    {
        return $this->specialiteId;
    }

    public function getStructureId(): ?UuidInterface
    {
        return $this->structureId;
    }

    public function getRppsId(): ?string
    {
        return $this->rppsId;
    }

    public function isOrganisation(): bool
    {
        return $this->organisation;
    }

    public function isNouveauPatient(): bool
    {
        return $this->nouveauPatient;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function getSpecialiteLibelle(): string
    {
        return $this->specialiteLibelle;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->toString(),
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'ville' => $this->ville,
            'email' => $this->email,
            'telephone' => $this->telephone,
            'specialiteId' => $this->specialiteId,
            'specialiteLibelle' => $this->specialiteLibelle,
            'structureId' => $this->structureId?->toString(),
            'rppsId' => $this->rppsId,
            'organisation' => $this->organisation,
            'nouveauPatient' => $this->nouveauPatient,
            'titre' => $this->titre,
        ];
    }
}