<?php

namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\core\application\ports\api\ServiceRdvInterface;

class HonorerRDVAction
{
    public function __construct(
        private ServiceRdvInterface $serviceRdv
    ) {}

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $id = $args['id'] ?? '';
            $rdv = $this->serviceRdv->honorerRendezVous($id);
            $response->getBody()->write(json_encode([
                'success' => true,
                'data' => $rdv->toArray()
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (\DomainException $e) {
            $status = $e->getMessage() === 'rdv_introuvable' ? 404 : 400;
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
        } catch (\Throwable $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => 'erreur_interne : ' . $e->getMessage()
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}


