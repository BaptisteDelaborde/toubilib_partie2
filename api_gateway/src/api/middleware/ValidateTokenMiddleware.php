<?php

namespace gateway\api\middleware;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class ValidateTokenMiddleware implements MiddlewareInterface
{
    private Client $authClient;

    public function __construct(Client $authClient)
    {
        $this->authClient = $authClient;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = $request->getUri()->getPath();

        if (!str_starts_with($path, '/rdvs')) {
            return $handler->handle($request);
        }

        // Extraire le token du header Authorization
        $authHeader = $request->getHeaderLine('Authorization');
        
        if (empty($authHeader)) {
            $response = new Response();
            $response->getBody()->write(json_encode([
                'error' => 'Token manquant',
                'message' => 'Le header Authorization est requis'
            ], JSON_PRETTY_PRINT));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401);
        }

        // Extraire le token du format "Bearer {token}"
        $tokenParts = sscanf($authHeader, "Bearer %s");
        $token = $tokenParts[0] ?? null;

        if (empty($token)) {
            $response = new Response();
            $response->getBody()->write(json_encode([
                'error' => 'Token invalide',
                'message' => 'Le format du token est invalide. Format attendu: Bearer {token}'
            ], JSON_PRETTY_PRINT));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401);
        }

        // Valider le token via le service app-auth
        try {
            $validationResponse = $this->authClient->request('POST', '/tokens/validate', [
                'json' => ['token' => $token],
                'http_errors' => false
            ]);

            $statusCode = $validationResponse->getStatusCode();

            if ($statusCode === 200) {
                // Token valide, continuer avec la requête
                return $handler->handle($request);
            } else {
                // Token invalide, retourner l'erreur du service d'authentification
                $errorBody = $validationResponse->getBody()->getContents();
                $response = new Response();
                $response->getBody()->write($errorBody);
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(401);
            }
        } catch (RequestException $e) {
            // Erreur de communication avec le service d'authentification
            $response = new Response();
            $response->getBody()->write(json_encode([
                'error' => 'Erreur de validation',
                'message' => 'Impossible de valider le token'
            ], JSON_PRETTY_PRINT));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401);
        }
    }
}
