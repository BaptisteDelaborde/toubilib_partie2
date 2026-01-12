<?php

namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\core\application\ports\api\ServicePraticienInterface;

class ListePraticiensAction
{
    public function __construct(
        private ServicePraticienInterface $servicePraticien
    ) {}

    public function __invoke(Request $request, Response $response): Response
    {
        try {
            $params = $request->getQueryParams();

            $specialite = $params['specialite'] ?? null;
            $ville = $params['ville'] ?? null;

            if ($specialite !== null || $ville !== null) {
                $praticiens = $this->servicePraticien->rechercherPraticiens($specialite, $ville);
            } else {
                $praticiens = $this->servicePraticien->listerPraticiens();
            }
            
            $data = array_map(function ($praticien) {
                return $praticien->toArray();
            }, $praticiens);

            $response->getBody()->write(json_encode([
                'success' => true,
                'data' => $data,
                'count' => count($data)
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);

        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => 'Erreur lors de la récupération des praticiens',
                'message' => $e->getMessage()
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }
}

