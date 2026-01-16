<?php

namespace toubilib\api\providers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException ;
use Firebase\JWT\BeforeValidException;

class JWTManager{
    private string $key;
    private string $alg;

    public function __construct(string $key, string $alg = 'HS256'){
        $this->key = $key;
        $this->alg = $alg;
    }

    public function generateToken(array $payload): string {
        return JWT::encode($payload, $this->key, $this->alg);
    }

    public function createAccesToken(array $payload): string {
        $payload['type'] = 'access';
        return $this->generateToken($payload);
    }

    public function createRefreshToken(array $payload): string {
        $payload['type'] = 'refresh';
        return $this->generateToken($payload);
    }

    public function decodeToken(string $token): array {
        try {
            $decoded = JWT::decode($token, new Key($this->key, $this->alg));
            return (array) $decoded;
        } catch (ExpiredException $e) {
            throw new \Exception("Token expiré : " . $e->getMessage());
        }catch(SignatureInvalidException $e1){
            throw new \Exception("Erreur de la signature du token : " . $e1->getMessage());
        }catch (BeforeValidException $e2){
            throw new \Exception("Token pas encore valide : " . $e2->getMessage());
        } catch (\UnexpectedValueException $e3){
            throw new \Exception("Valeur non attendue : " . $e3->getMessage());
        }
    }
}
