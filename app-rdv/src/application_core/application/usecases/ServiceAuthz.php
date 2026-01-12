<?php
declare(strict_types=1);

namespace toubilib\core\application\usecases;

use toubilib\core\application\ports\api\ProfileDTO;
use toubilib\core\application\ports\api\ServiceAuthzInterface;
use toubilib\core\application\ports\api\ServicePraticienInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\RdvRepositoryInterface;
use toubilib\core\domain\entities\Rdv;

class ServiceAuthz implements ServiceAuthzInterface
{
    public const ROLE_PATIENT = 1;
    public const ROLE_PRATICIEN = 10;

    public function __construct(
        private readonly RdvRepositoryInterface $rdvRepository,
        private readonly ?ServicePraticienInterface $servicePraticien = null
    ) {
    }

    public function canAccessPraticienAgenda(ProfileDTO $profile, string $praticienId): bool
    {
        if ($this->servicePraticien !== null) {
            $praticien = $this->servicePraticien->getPraticienDetail($praticienId);
            if ($praticien === null) {
                return false;
            }
        }

        return in_array($profile->role, [self::ROLE_PATIENT, self::ROLE_PRATICIEN], true);
    }

    public function canAccessRdvDetail(ProfileDTO $profile, string $rdvId): bool
    {
        $rdv = $this->rdvRepository->findById($rdvId);
        if ($rdv === null) {
            return false;
        }

        if ($profile->role === self::ROLE_PATIENT) {
            return $rdv->getPatientId() === $profile->id;
        }

        if ($profile->role === self::ROLE_PRATICIEN) {
            return $rdv->getPraticienId() === $profile->id;
        }

        return false;
    }

    public function canAccessPatientConsultations(ProfileDTO $profile, string $patientId): bool {
        return $profile->role === self::ROLE_PATIENT && $profile->id === $patientId;
    }

}


