<?php

namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\core\application\ports\api\ServiceRdvInterface;

class AgendaPraticienAction
{
    public function __construct(
        private ServiceRdvInterface $serviceRdv
    ) {}

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $praticienId = $args['id'] ?? '';
            $params = $request->getQueryParams();
            $debut = $params['debut'] ?? null;
            $fin = $params['fin'] ?? null;

            $normalize = function (?string $d, bool $isStart): ?string {
                if ($d === null) return null;
                $d = str_replace('Z', '', $d);
                $d = str_replace('T', ' ', $d);
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $d) === 1) {
                    return $isStart ? ($d . ' 00:00:00') : ($d . ' 23:59:59');
                }
                return $d;
            };
            $debut = $normalize($debut, true);
            $fin = $normalize($fin, false);

            $items = $this->serviceRdv->agendaPraticien($praticienId, $debut, $fin);
            $data = array_map(fn($i) => $i->toArray(), $items);

            $response->getBody()->write(json_encode([
                'success' => true,
                'data' => $data,
                'count' => count($data)
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (\Throwable $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => 'erreur_interne',
                'message' => $e->getMessage()
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}


