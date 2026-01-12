<?php

namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpInternalServerErrorException;
use toubilib\core\application\ports\api\ServiceRdvInterface;

class CreerRendezVousAction
{
    private ServiceRdvInterface $serviceRdv;

    public function __construct(ServiceRdvInterface $serviceRdv)
    {
        $this->serviceRdv = $serviceRdv;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $dto = $request->getAttribute('inputRdvDTO');

        try {
            $rdv = $this->serviceRdv->creerRendezVous($dto);

            $response->getBody()->write(json_encode([
                'status' => 'success',
                'data'   => $rdv->toArray()
            ], JSON_PRETTY_PRINT));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withHeader('Location', '/rdv/' . $rdv->id)
                ->withStatus(201);

        } catch (\Throwable $e) {
            throw new HttpInternalServerErrorException($request, "Erreur interne : " . $e->getMessage(), $e);
        }
    }
}
