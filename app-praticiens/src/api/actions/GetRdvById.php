<?php

namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\core\application\ports\api\ServiceRdvInterface;
use toubilib\core\domain\exceptions\NotFoundException;

class GetRdvById
{
    public function __construct(
        private readonly ServiceRdvInterface $serviceRdv
    ) {}

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $id = $args['id'] ?? null;
            $rdv = $this->serviceRdv->chercherParId($id);

            if($rdv === NULL){
                throw new NotFoundException();
            }

            $response->getBody()->write(json_encode([
                'success' => true,
                'data' => $rdv->toArray()
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);

        } catch(NotFoundException $e){
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => 'Rdv non trouvé'
            ]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);

        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => 'Erreur lors de la récupération du rendez-vous',
                'message' => $e->getMessage()
            ]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }
}
