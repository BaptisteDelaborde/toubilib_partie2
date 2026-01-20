<?php

namespace gateway\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpInternalServerErrorException;

class GenericGatewayAction {
    private Client $praticiensClient;
    private Client $rdvClient;
    private Client $toubilibClient;
    private Client $authClient;

    public function __construct(Client $praticiensClient, Client $rdvClient, Client $toubilibClient, Client $authClient){
        $this->praticiensClient = $praticiensClient;
        $this->rdvClient = $rdvClient;
        $this->toubilibClient = $toubilibClient;
        $this->authClient = $authClient;
    }

    public function __invoke(Request $request, Response $response, array $args): Response {
        $method = $request->getMethod();
        $path = '/' . ($args['routes'] ?? '');

        $client = $this->selectClient($path);

        $headers = $request->getHeaders();
        unset($headers['Host']);
        unset($headers['Content-Length']);

        $options = [
            'query' => $request->getQueryParams(),
            'headers' => $headers,
            'http_errors' => true
        ];

        if (in_array($method, ['POST', 'PUT', 'PATCH'])) {
            $parsedBody = $request->getParsedBody();
            if (!empty($parsedBody)) {
                // Utiliser 'json' pour encoder automatiquement en JSON et définir le Content-Type
                $options['json'] = $parsedBody;
            } else {
                // Si pas de parsedBody, utiliser le body brut
                $body = $request->getBody();
                $body->rewind(); // Réinitialiser le pointeur
                if ($body->getSize() > 0) {
                    $options['body'] = $body->getContents();
                }
            }
        }

        try {
            $apiResponse = $client->request($method, $path, $options);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
                if ($statusCode === 404) {
                    throw new HttpNotFoundException($request, "Ressource introuvable sur le service distant : $path");
                }
            }
            throw new HttpInternalServerErrorException($request, "Erreur Gateway vers : $path", $e);
        }
        $response->getBody()->write($apiResponse->getBody()->getContents());
        return $response->withStatus($apiResponse->getStatusCode())
            ->withHeader('Content-Type', 'application/json');
    }

    /**
     * Sélectionne le bon client par rapport au chemin
     */
    private function selectClient(string $path): Client
    {
        // app-rdv
        if (str_starts_with($path, '/rdvs') || str_contains($path, '/rdvs')) {
            return $this->rdvClient;
        }
        // app-praticiens
        if (str_starts_with($path, '/praticiens')) {
            return $this->praticiensClient;
        }
        // app-auth
        if (str_starts_with($path, '/signin')) {
            return $this->authClient;
        }
        if (str_starts_with($path, '/tokens')) {
            return $this->authClient;
        }
        // default api.toubilib
        return $this->toubilibClient;
    }
}