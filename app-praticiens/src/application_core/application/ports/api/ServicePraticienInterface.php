<?php

namespace toubilib\core\application\ports\api;

interface ServicePraticienInterface
{
    /**
     * Retourne la liste complète des praticiens avec leurs informations de base
     * 
     * @return PraticienDTO[]
     */
    public function listerPraticiens(): array;

    /**
     * Retourne les détails d'un praticien par son ID
     *
     * @param string $id
     * @return PraticienDetailDTO|null
     */
    public function getPraticienDetail(string $id): ?PraticienDetailDTO;

    /**
     * Retourne la liste des praticien selon la specialite ou la ville
     *
     * @param int|null $specialite
     * @param string|null $ville
     * @return array
     */
    public function rechercherPraticiens(?int $specialite, ?string $ville): array;

}
