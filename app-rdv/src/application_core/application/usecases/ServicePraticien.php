<?php

namespace toubilib\core\application\usecases;

use toubilib\core\application\ports\api\PraticienDetailDTO;
use toubilib\core\application\ports\api\PraticienDTO;
use toubilib\core\application\ports\api\ServicePraticienInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface;

class ServicePraticien implements ServicePraticienInterface
{
    private PraticienRepositoryInterface $praticienRepository;

    public function __construct(PraticienRepositoryInterface $praticienRepository)
    {
        $this->praticienRepository = $praticienRepository;
    }

    public function listerPraticiens(): array
    {
        $praticiens = $this->praticienRepository->findAll();
        
        return array_map(function ($praticien) {
            return new PraticienDTO(
                id: $praticien->getId()->toString(),
                nom: $praticien->getNom(),
                prenom: $praticien->getPrenom(),
                ville: $praticien->getVille(),
                email: $praticien->getEmail(),
                specialite: $praticien->getSpecialiteLibelle() // Cette méthode sera ajoutée à l'entité
            );
        }, $praticiens);
    }

    public function getPraticienDetail(string $id): ?PraticienDetailDTO
    {
        return $this->praticienRepository->findDetailById($id);
    }

    public function rechercherPraticiens(?int $specialite, ?string $ville): array
    {
        return $this->praticienRepository->findByParam($specialite, $ville);
    }

}