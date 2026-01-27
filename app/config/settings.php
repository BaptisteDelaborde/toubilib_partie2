<?php

use Psr\Container\ContainerInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\PatientRepositoryInterface;
use toubilib\infra\repositories\PDOPatientRepository;

return [
    'db' => [
        'toubipat' => [
            'driver' => 'pgsql',
            'host' => $_ENV['TOUBIRDV_DB_HOST'] ?? 'toubipat.db',
            'port' => $_ENV['TOUBIRDV_DB_PORT'] ?? 5432,
            'dbname' => $_ENV['TOUBIRDV_DB_NAME'] ?? 'toubipat',
            'user' => $_ENV['TOUBIRDV_DB_USER'] ?? 'toubipat',
            'password' => $_ENV['TOUBIRDV_DB_PASS'] ?? 'toubipat',
        ]
    ],

    // Options PDO communs
    'pdo_options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],

    // Connexion toubipat
    'db.toubipat' => function (ContainerInterface $c): PDO {
    $config = $c->get('db')['toubipat'];
    $options = $c->get('pdo_options');

    $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";
    return new PDO($dsn, $config['user'], $config['password'], $options);
    },

    // Repository Patient
    PatientRepositoryInterface::class => function (ContainerInterface $c) {
    return new PDOPatientRepository($c->get('db.toubipat'));
    },
];


