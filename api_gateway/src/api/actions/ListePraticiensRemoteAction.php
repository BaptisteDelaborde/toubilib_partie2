<?php

namespace gateway\api\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\gateway\application\interface\ClientInterface;

class ListePraticiensRemoteAction
{
    private ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {

        $queryParams = $request->getQueryParams();

        $remoteResponse = $this->client->get('/praticiens', $queryParams);

        $response->getBody()->write(
            (string) $remoteResponse->getBody()
        );

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($remoteResponse->getStatusCode());
    }
}