<?php

namespace toubilib\api\middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;
use Slim\Exception\HttpBadRequestException;
use toubilib\core\application\ports\api\InputRendezVousDTO;

class ValidateRDVMiddleware
{
    public function __invoke(ServerRequestInterface $rq, RequestHandlerInterface $next): ResponseInterface
    {
        $data = $rq->getParsedBody() ?? null;

        try {
            v::key('praticien_id', v::stringType()->notEmpty())
                ->key('patient_id', v::stringType()->notEmpty())
                ->key('date_heure_debut', v::dateTime('Y-m-d H:i:s'))
                ->key('duree', v::intType()->positive())
                ->key('motif_visite', v::stringType()->notEmpty())
                ->assert($data);
        } catch (NestedValidationException $e) {
            throw new HttpBadRequestException($rq, "Invalid data: " . $e->getFullMessage(), $e);
        }

        // Protection des données (sanitization)
        $data['praticien_id']   = filter_var($data['praticien_id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data['patient_id']     = filter_var($data['patient_id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data['motif_visite']   = filter_var($data['motif_visite'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // Génération des valeurs manquantes
        $dateDebut = $data['date_heure_debut'];
        $duree     = (int) $data['duree'];
        $dateFin = date("Y-m-d H:i:s", strtotime($dateDebut . " + {$duree} minutes"));
        $dateCreation = $data['date_creation'] ?? date("Y-m-d H:i:s");

        // Création du DTO
        $rdvDTO = new InputRendezVousDTO(
            praticien_id: $data['praticien_id'],
            patient_id: $data['patient_id'],
            date_heure_debut: $dateDebut,
            duree: $duree,
            date_heure_fin: $dateFin,
            date_creation: $dateCreation,
            motif_visite: $data['motif_visite']
        );

        // On stocke le DTO dans la requête
        $rq = $rq->withAttribute('inputRdvDTO', $rdvDTO);

        return $next->handle($rq);
    }
}
