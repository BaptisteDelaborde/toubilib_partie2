<?php

namespace gateway\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class DetailPraticienRemoteAction {

    private Client $client;

    public function __construct(Client $client){
        $this->client = $client;
    }

    public function __invoke(Request $request, Response $response, array $args): Response{
        $id = $args['id'];

        try {
            $apiResponse = $this->client->get("/praticiens/$id");
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response->getBody()->write($e->getResponse()->getBody()->getContents());
                return $response->withStatus($e->getResponse()->getStatusCode())
                    ->withHeader('Content-Type', 'application/json');
            }

            $response->getBody()->write(json_encode(['error' => 'Service unavailable']));
            return $response->withStatus(503)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write($apiResponse->getBody()->getContents());
        return $response->withStatus($apiResponse->getStatusCode())
            ->withHeader('Content-Type', 'application/json');
    }
}