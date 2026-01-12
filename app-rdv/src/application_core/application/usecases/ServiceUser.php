<?php

namespace toubilib\core\application\usecases;
use toubilib\core\application\ports\api\CredentialsDTO;
use toubilib\core\application\ports\api\InputRendezVousDTO;
use toubilib\core\application\ports\api\ProfileDTO;
use toubilib\core\application\ports\api\RdvDTO;
use toubilib\core\application\ports\api\ServiceUserInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\AuthRepositoryInterface;

class ServiceUser implements ServiceUserInterface {

    private AuthRepositoryInterface $userRepository;

    public function __construct(AuthRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(CredentialsDTO $credentials, int $role): ProfileDTO{

        $this->userRepository->save($credentials, $role);
        $user = $this->userRepository->findByEmail($credentials->email);

        return new ProfileDTO(
            $user->getId(),
            $user->getEmail(),
            $user->getRole()
        );
    }

    public function byCredentials(CredentialsDTO $credentials): ?ProfileDTO{
        $user = $this->userRepository->findByEmail($credentials->email);
        if($user === null){
            throw new \Exception("Email éroné");
        }

        if(!password_verify($credentials->password, $user->getPassword())){
            throw new \Exception("Mot de passe éroné");
        }

        return new ProfileDTO(
            $user->getId(),
            $user->getEmail(),
            $user->getRole()
        );
    }
}