<?php

namespace gateway\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpInternalServerErrorException;

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
            if ($e->hasResponse() && $e->getResponse()->getStatusCode() === 404) {
                throw new HttpNotFoundException($request, "Le praticien $id n'existe pas.");
            }
            throw new HttpInternalServerErrorException($request, "Erreur de communication avec le service praticiens.", $e);
        }

        $response->getBody()->write($apiResponse->getBody()->getContents());
        return $response->withStatus($apiResponse->getStatusCode())
            ->withHeader('Content-Type', 'application/json');
    }
}