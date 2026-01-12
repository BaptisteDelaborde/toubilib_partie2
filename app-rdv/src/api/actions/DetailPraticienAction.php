<?php
namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\core\application\ports\api\ServicePraticienInterface;
use toubilib\core\domain\exceptions\NotFoundException;
use toubilib\core\domain\exceptions\ArgumentInvalideException;

class DetailPraticienAction
{
    public function __construct(
        private readonly ServicePraticienInterface $servicePraticien
    ) {}

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $id = $args['id'] ?? null;

            if (!$id) {
                throw new ArgumentInvalideException("manque l'ID du praticien");
            }

            $detail = $this->servicePraticien->getPraticienDetail($id);

            if ($detail === null) {
                throw new NotFoundException("Praticien not found");
            }

            $response->getBody()->write(json_encode([
                'success' => true,
                'data'    => $detail->toArray()
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);

        } catch (NotFoundException $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error'   => 'Praticien non trouvé'
            ]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);

        } catch (ArgumentInvalideException $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error'   => $e->getMessage()
            ]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);

        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error'   => 'Erreur interne lors de la récupération du praticien',
                'message' => $e->getMessage() 
            ]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }
}
