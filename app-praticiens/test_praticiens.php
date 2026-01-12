<?php

require_once 'vendor/autoload.php';

use toubilib\infra\adapters\DatabaseConnection;
use toubilib\infra\repositories\PDOPraticienRepository;
use toubilib\core\application\usecases\ServicePraticien;

// Charger la configuration
$config = require 'config/database.php';
DatabaseConnection::init($config);

try {
    echo "=== Test de la fonctionnalité 1 : Lister les praticiens ===\n\n";
    
    // 1. Test du repository
    echo "1. Test du repository PDOPraticienRepository...\n";
    $pdo = DatabaseConnection::getConnection('toubiprat');
    $repository = new PDOPraticienRepository($pdo);
    $praticiens = $repository->findAll();
    echo "✅ Repository fonctionne : " . count($praticiens) . " praticiens trouvés\n\n";
    
    // 2. Test du service métier
    echo "2. Test du service métier ServicePraticien...\n";
    $service = new ServicePraticien($repository);
    $praticiensDTO = $service->listerPraticiens();
    echo "✅ Service fonctionne : " . count($praticiensDTO) . " DTOs créés\n\n";
    
    // 3. Affichage des premiers praticiens
    echo "3. Aperçu des premiers praticiens :\n";
    for ($i = 0; $i < min(3, count($praticiensDTO)); $i++) {
        $praticien = $praticiensDTO[$i];
        echo "   - " . $praticien->nom . " " . $praticien->prenom . " (" . $praticien->specialite . ") - " . $praticien->ville . "\n";
    }
    echo "\n";
    
    echo "🎉 Tous les tests sont passés avec succès !\n";
    echo "L'API est prête à être utilisée sur http://localhost:6080/praticiens\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors du test: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

