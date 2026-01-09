<?php

namespace toubilib\gateway\infrastructure\http;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use toubilib\gateway\application\interface\ClientInterface;

class GuzzleClient implements ClientInterface
{
    private Client $client;

    public function __construct(string $baseUri)
    {
        $this->client = new Client([
            'base_uri' => $baseUri,
            'timeout'  => 5.0,
        ]);
    }

    public function get(string $uri): ResponseInterface
    {
        return $this->client->get($uri);
    }
}
