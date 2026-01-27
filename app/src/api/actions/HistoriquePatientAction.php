<?php
declare(strict_types=1);

namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\core\application\ports\api\ServiceRdvInterface;

class HistoriquePatientAction
{
    public function __construct(
        private ServiceRdvInterface $serviceRdv
    ) {}

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $patientId = $args['id'];

        $consultations = $this->serviceRdv->getHistoriquePatient($patientId);

        $response->getBody()->write(json_encode($consultations));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
