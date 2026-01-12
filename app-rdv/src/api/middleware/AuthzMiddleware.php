<?php

namespace toubilib\api\middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteContext;
use toubilib\core\application\usecases\ServiceAuthz;
use toubilib\core\application\ports\api\ProfileDTO;

class AuthzMiddleware
{
    private ServiceAuthz $authzService;

    public function __construct(ServiceAuthz $authzService)
    {
        $this->authzService = $authzService;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        try {
            $profile = $request->getAttribute('profile');
            error_log('Attributs de la requête : ' . print_r($request->getAttributes(), true));

            if (!$profile instanceof ProfileDTO) {
                throw new \Exception("Utilisateur non authentifié", 401);
            }

            $routeContext = RouteContext::fromRequest($request);
            $route = $routeContext->getRoute();

            if (!$route) {
                throw new \Exception("Route non trouvée", 404);
            }

            $routeName = $route->getName();

            switch ($routeName) {
                case 'praticien.aganda':
                    $praticienId = $route->getArgument('id');
                    $isAllowed = $this->authzService->canAccessPraticienAgenda($profile, $praticienId);
                    break;

                case 'rdvs.id':
                    $rdvId = $route->getArgument('id');
                    $isAllowed = $this->authzService->canAccessRdvDetail($profile, $rdvId);
                    break;
                case 'patients.consultations':
                    $patientId = $route->getArgument('id');
                    $isAllowed = $this->authzService->canAccessPatientConsultations($profile, $patientId);
                    break;
                default:
                    throw new \Exception("Aucune règle d’autorisation définie pour cette route", 403);
            }

            if (!$isAllowed) {
                $response = new \Slim\Psr7\Response();
                $response->getBody()->write(json_encode([
                    'error' => 'Accès refusé, droit obtenu invalide'
                ], JSON_PRETTY_PRINT));
                return $response->withHeader('Content-Type', 'application/json')
                    ->withStatus(403);
            }

            return $handler->handle($request);

        } catch (\Exception $e) {
            $response = new \Slim\Psr7\Response();
            $status = $e->getCode() ?: 403;
            $response->getBody()->write(json_encode([
                'error' => $e->getMessage()
            ], JSON_PRETTY_PRINT));
            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus($status);
        }
    }
}
