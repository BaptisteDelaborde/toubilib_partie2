<?php
namespace toubilib\api\providers;

use Firebase\JWT\JWT;
use toubilib\core\application\ports\api\AuthDTO;
use toubilib\core\application\ports\api\CredentialsDTO;
use toubilib\core\application\ports\api\ProfileDTO;
use toubilib\core\application\ports\api\ServiceUserInterface;

class JWTAuthnProvider implements AuthnProviderInterface{

    private ServiceUserInterface $serviceUser;
    private JWTManager $JWTManager;

    public function __construct(JWTManager $jwtManager, ServiceUserInterface $serviceUser){
        $this->JWTManager = $jwtManager;
        $this->serviceUser = $serviceUser;
    }

    public function signin(CredentialsDTO $credentials): array
    {
        $user = $this->serviceUser->byCredentials($credentials);
        $payload = [
            'iss' => 'http://toubilib',
            'iat' => time(),
            'exp' => time()+3600,
            'sub' => $user->id,
            'data' => [
                'role' => $user->role,
                'user' => $user->email
            ]
        ];
        $accessToken  = $this->JWTManager->createAccesToken($payload);
        $refreshToken = $this->JWTManager->createRefreshToken($payload);

        return [new AuthDTO($accessToken, $refreshToken), new ProfileDTO($user->id,$user->email,$user->role)];
    }
}