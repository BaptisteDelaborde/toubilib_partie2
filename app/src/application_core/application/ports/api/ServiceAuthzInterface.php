<?php
declare(strict_types=1);

namespace toubilib\core\application\ports\api;

interface ServiceAuthzInterface
{
    /**
     * Vérifie si le profil peut accéder à l'agenda du praticien donné.
     */
    public function canAccessPraticienAgenda(ProfileDTO $profile, string $praticienId): bool;

    /**
     * Vérifie si le profil peut accéder au détail d'un rendez-vous donné.
     */
    public function canAccessRdvDetail(ProfileDTO $profile, string $rdvId): bool;
}


