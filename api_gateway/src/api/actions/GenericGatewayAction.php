<?php

namespace gateway\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpInternalServerErrorException;

class GenericGatewayAction {
    private Client $client;

    public function __construct(Client $client){
        $this->client = $client;
    }

    public function __invoke(Request $request, Response $response, array $args): Response {
        $method = $request->getMethod();
        $path = $args['routes'] ?? '';

        $headers = $request->getHeaders();
        unset($headers['Host']);
        unset($headers['Content-Length']);

        try {
            $apiResponse = $this->client->request($method, $path, [
                'query' => $request->getQueryParams(),
                'headers' => $headers,
                'body' => $request->getBody(),
                'http_errors' => true
            ]);
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
}