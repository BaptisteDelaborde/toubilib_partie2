<?php

namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\api\providers\AuthnProviderInterface;
use toubilib\core\application\ports\api\CredentialsDTO;

class SigninAction
{
    public function __construct(
        private readonly AuthnProviderInterface $authnProvider
    )
    {}

    public function __invoke(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();
            $email = $data['email'] ?? '';
            $password = $data['password'] ?? '';

            if (($email==='') OR ($password==='')){
                throw new \Exception("Email ou mot de passe non fourni");
            }
            $credentials = new CredentialsDTO($data['email'], $data['password']);
            $resSignIn = $this->authnProvider->signin($credentials);

            $authDTO = $resSignIn[0];
            $profile = $resSignIn[1];
            $payload = [
                'access_token'  => $authDTO->accesToken,
                'refresh_token' => $authDTO->refreshToken,
            ];

            $res = [
                'payload' => $payload,
                'profile' => $profile
            ];

            $response->getBody()->write(json_encode($res, JSON_PRETTY_PRINT));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);


        }catch (\Exception $e){
            $response->getBody()->write($e->getMessage());
            return $response->withStatus(400);
        }

    }
}
