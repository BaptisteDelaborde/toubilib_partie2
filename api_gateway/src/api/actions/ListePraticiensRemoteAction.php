<?php

namespace gateway\api\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpInternalServerErrorException;

class ListePraticiensRemoteAction
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {

        $queryParams = $request->getQueryParams();

        try {
            $remoteResponse = $this->client->get('/praticiens', [
                'query' => $queryParams
            ]);
        } catch (RequestException $e) {
            if ($e->hasResponse() && $e->getResponse()->getStatusCode() === 404) {
                throw new HttpNotFoundException($request, "Aucun praticien trouvé");
            }
            throw new HttpInternalServerErrorException($request, "Erreur de communication avec le service praticiens", $e);
        }

        $response->getBody()->write(
            (string) $remoteResponse->getBody()
        );

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($remoteResponse->getStatusCode());
    }
}