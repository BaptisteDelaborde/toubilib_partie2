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
    private Client $toubilibClient;

    public function __construct(Client $praticiensClient, Client $toubilibClient){
        $this->praticiensClient = $praticiensClient;
        $this->toubilibClient = $toubilibClient;
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
            $body = $request->getBody();
            if ($body->getSize() > 0) {
                $options['body'] = $body;
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
     * Sélectionne le bon client par rapprot au chemin
     */
    private function selectClient(string $path): Client
    {
        if (str_starts_with($path, '/praticiens')) {
            return $this->praticiensClient;
        }
        return $this->toubilibClient;
    }
}