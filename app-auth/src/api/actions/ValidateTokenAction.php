<?php

namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\api\providers\JWTManager;

class ValidateTokenAction
{
    public function __construct(
        private readonly JWTManager $jwtManager
    )
    {}

    public function __invoke(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();
            $token = $data['token'] ?? '';
            
            if (empty($token)) {
                $response->getBody()->write(json_encode([
                    'error' => 'Token manquant',
                    'message' => 'Le token est requis'
                ], JSON_PRETTY_PRINT));
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(401);
            }
            
            $payload = $this->jwtManager->decodeToken($token);
            $response->getBody()->write(json_encode($payload, JSON_PRETTY_PRINT));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $errorType = 'Token invalide';
            $errorMessage = $message;

            if (str_contains($message, 'Token expiré') || str_contains($message, 'expired')) {
                $errorType = 'Token expiré';
                $errorMessage = 'Le token a expiré';
            } elseif (str_contains($message, 'signature') || str_contains($message, 'Signature')) {
                $errorType = 'Signature invalide';
                $errorMessage = 'La signature du token est invalide';
            } elseif (str_contains($message, 'pas encore valide') || str_contains($message, 'before_valid')) {
                $errorType = 'Token pas encore valide';
                $errorMessage = 'Le token n\'est pas encore valide';
            } elseif (str_contains($message, 'Valeur non attendue') || str_contains($message, 'UnexpectedValue')) {
                $errorType = 'Token invalide';
                $errorMessage = 'Le format du token est invalide';
            }

            $response->getBody()->write(json_encode([
                'error' => $errorType,
                'message' => $errorMessage
            ], JSON_PRETTY_PRINT));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401);
        }
    }
}