<?php

namespace gateway\api\actions;


Class SigninAction {

    private Client $client;

    public function __construct(Client $client){
        $this->client = $client;
    }

    public function __invoke(Request $request, Response $response, array $args): Response{
        $id = $args['id'];

        try {
            $apiResponse = $this->client->get("/signin/$id");
        } catch (RequestException $e) {
            if ($e->hasResponse() && $e->getResponse()->getStatusCode() === 404) {
                throw new HttpNotFoundException($request, "non trouvé");
            }
            throw new HttpInternalServerErrorException($request, "Erreur de communication avec le service auth.", $e);
        }

        $response->getBody()->write($apiResponse->getBody()->getContents());
        return $response->withStatus($apiResponse->getStatusCode())
            ->withHeader('Content-Type', 'application/json');
    }
}