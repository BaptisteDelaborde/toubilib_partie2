<?php

namespace toubilib\infra\adapters;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use toubilib\core\application\ports\api\PraticienDetailDTO;
use toubilib\core\application\ports\api\PraticienDTO;
use toubilib\core\application\ports\api\ServicePraticienInterface;

class PraticienRemoteAdapter implements ServicePraticienInterface
{
    private Client $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function listerPraticiens(): array
    {
        try {
            $response = $this->httpClient->request('GET', '/praticiens');
            $body = json_decode($response->getBody()->getContents(), true);

            if (!isset($body['success']) || !$body['success'] || !isset($body['data'])) {
                return [];
            }

            return array_map(function ($data) {
                return new PraticienDTO(
                    id: $data['id'],
                    nom: $data['nom'],
                    prenom: $data['prenom'],
                    ville: $data['ville'],
                    email: $data['email'],
                    specialite: $data['specialite']
                );
            }, $body['data']);
        } catch (RequestException $e) {
            throw new \RuntimeException("Erreur lors de la récupération des praticiens: " . $e->getMessage(), 0, $e);
        }
    }

    public function getPraticienDetail(string $id): ?PraticienDetailDTO
    {
        try {
            $response = $this->httpClient->request('GET', "/praticiens/{$id}");
            $body = json_decode($response->getBody()->getContents(), true);

            if (!isset($body['success']) || !$body['success'] || !isset($body['data'])) {
                return null;
            }

            $data = $body['data'];
            return new PraticienDetailDTO(
                id: $data['id'],
                nom: $data['nom'],
                prenom: $data['prenom'],
                specialite: $data['specialite'],
                email: $data['email'],
                telephone: $data['telephone'] ?? '',
                ville: $data['ville'],
                codePostal: $data['code_postal'] ?? null,
                structure: $data['structure'] ?? null,
                motifs: $data['motifs'] ?? [],
                moyensPaiement: $data['moyens_paiement'] ?? []
            );
        } catch (RequestException $e) {
            if ($e->hasResponse() && $e->getResponse()->getStatusCode() === 404) {
                return null;
            }
            throw new \RuntimeException("Erreur lors de la récupération du praticien: " . $e->getMessage(), 0, $e);
        }
    }

    public function rechercherPraticiens(?int $specialite, ?string $ville): array
    {
        try {
            $queryParams = [];
            if ($specialite !== null) {
                $queryParams['specialite'] = $specialite;
            }
            if ($ville !== null) {
                $queryParams['ville'] = $ville;
            }

            $response = $this->httpClient->request('GET', '/praticiens', [
                'query' => $queryParams
            ]);
            $body = json_decode($response->getBody()->getContents(), true);

            if (!isset($body['success']) || !$body['success'] || !isset($body['data'])) {
                return [];
            }

            return array_map(function ($data) {
                return new PraticienDTO(
                    id: $data['id'],
                    nom: $data['nom'],
                    prenom: $data['prenom'],
                    ville: $data['ville'],
                    email: $data['email'],
                    specialite: $data['specialite']
                );
            }, $body['data']);
        } catch (RequestException $e) {
            throw new \RuntimeException("Erreur lors de la recherche des praticiens: " . $e->getMessage(), 0, $e);
        }
    }
}
