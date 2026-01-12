<?php

use Psr\Container\ContainerInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface;
use toubilib\infra\repositories\PDOPraticienRepository;

return [
    'db' => [
        'toubiprat' => [
            'driver' => 'pgsql',
            'host' => $_ENV['TOUBIPRAT_DB_HOST'] ?? 'toubiprati.db',
            'port' => $_ENV['TOUBIPRAT_DB_PORT'] ?? 5432,
            'dbname' => $_ENV['TOUBIPRAT_DB_NAME'] ?? 'toubiprat',
            'user' => $_ENV['TOUBIPRAT_DB_USER'] ?? 'toubiprat',
            'password' => $_ENV['TOUBIPRAT_DB_PASS'] ?? 'toubiprat',
        ]
    ],

    // Options PDO communs
    'pdo_options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],

    // Connexion toubiprat
    'db.toubiprat' => function (ContainerInterface $c): PDO {
        $config = $c->get('db')['toubiprat'];
        $options = $c->get('pdo_options');
        $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";
        return new PDO($dsn, $config['user'], $config['password'], $options);
    },

    // Repository Praticien
    PraticienRepositoryInterface::class => function (ContainerInterface $c) {
        return new PDOPraticienRepository($c->get('db.toubiprat'));
    },
];


