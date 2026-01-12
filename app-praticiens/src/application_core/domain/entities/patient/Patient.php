<?php

namespace toubilib\core\domain\entities\patient;

class Patient
{
    private string $id;
    private string $nom;
    private string $prenom;
    private string $date_naissance;
    private string $adresse;
    private int $code_postal;
    private string $ville;
    private string $email;
    private string $telephone;
    public function __construct(
        string $id,
        string $nom,
        string $prenom,
        string $date_naissance,
        string $adresse,
        int $code_postal,
        string $ville,
        string $email,
        string $telephone
    )
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->date_naissance = $date_naissance;
        $this->adresse = $adresse;
        $this->code_postal = $code_postal;
        $this->ville = $ville;
        $this->email = $email;
        $this->telephone = $telephone;
    }

    public function getId(): string
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

    public function getDateNaissance(): string
    {
        return $this->date_naissance;
    }

    public function getAdresse(): string
    {
        return $this->adresse;
    }

    public function getCodePostal(): int
    {
        return $this->code_postal;
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
}