<?php

use Psr\Container\ContainerInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\AuthRepositoryInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\PatientRepositoryInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\RdvRepositoryInterface;
use toubilib\infra\repositories\PDOAuthReposiroty;
use toubilib\infra\repositories\PDOPatientRepository;
use toubilib\infra\repositories\PDOPraticienRepository;
use toubilib\infra\repositories\PDORdvRepository;

return [
    'db' => [
        'toubiprat' => [
            'driver' => 'pgsql',
            'host' => $_ENV['TOUBIPRAT_DB_HOST'] ?? 'toubiprati.db',
            'port' => $_ENV['TOUBIPRAT_DB_PORT'] ?? 5432,
            'dbname' => $_ENV['TOUBIPRAT_DB_NAME'] ?? 'toubiprat',
            'user' => $_ENV['TOUBIPRAT_DB_USER'] ?? 'toubiprat',
            'password' => $_ENV['TOUBIPRAT_DB_PASS'] ?? 'toubiprat',
        ],
        'toubirdv' => [
            'driver' => 'pgsql',
            'host' => $_ENV['TOUBIRDV_DB_HOST'] ?? 'toubirdv.db',
            'port' => $_ENV['TOUBIRDV_DB_PORT'] ?? 5432,
            'dbname' => $_ENV['TOUBIRDV_DB_NAME'] ?? 'toubirdv',
            'user' => $_ENV['TOUBIRDV_DB_USER'] ?? 'toubirdv',
            'password' => $_ENV['TOUBIRDV_DB_PASS'] ?? 'toubirdv',
        ],
        'toubipat' => [
            'driver' => 'pgsql',
            'host' => $_ENV['TOUBIRDV_DB_HOST'] ?? 'toubipat.db',
            'port' => $_ENV['TOUBIRDV_DB_PORT'] ?? 5432,
            'dbname' => $_ENV['TOUBIRDV_DB_NAME'] ?? 'toubipat',
            'user' => $_ENV['TOUBIRDV_DB_USER'] ?? 'toubipat',
            'password' => $_ENV['TOUBIRDV_DB_PASS'] ?? 'toubipat',
        ],
        'toubiauth' => [
            'driver' => 'pgsql',
            'host' => $_ENV['TOUBIAUTH_DB_HOST'] ?? 'toubiauth.db',
            'port' => $_ENV['TOUBIAUTH_DB_PORT'] ?? 5432,
            'dbname' => $_ENV['TOUBIAUTH_DB_NAME'] ?? 'toubiauth',
            'user' => $_ENV['TOUBIAUTH_DB_USER'] ?? 'toubiauth',
            'password' => $_ENV['TOUBIAUTH_DB_PASS'] ?? 'toubiauth',
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

    // Connexion toubirdv
    'db.toubirdv' => function (ContainerInterface $c): PDO {
    $config = $c->get('db')['toubirdv'];
    $options = $c->get('pdo_options');

    $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";
    return new PDO($dsn, $config['user'], $config['password'], $options);
    },

    // Connexion toubipat
    'db.toubipat' => function (ContainerInterface $c): PDO {
    $config = $c->get('db')['toubipat'];
    $options = $c->get('pdo_options');

    $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";
    return new PDO($dsn, $config['user'], $config['password'], $options);
    },

    // Connexion toubiauth
    'db.toubiauth' => function (ContainerInterface $c): PDO {
    $config = $c->get('db')['toubiauth'];
    $options = $c->get('pdo_options');
    $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";
    return new PDO($dsn, $config['user'], $config['password'], $options);
    },

    // Repository Praticien
    PraticienRepositoryInterface::class => function (ContainerInterface $c) {
    return new PDOPraticienRepository($c->get('db.toubiprat'));
    },

    // Repository Rdv
    RdvRepositoryInterface::class => function (ContainerInterface $c) {
    return new PDORdvRepository($c->get('db.toubirdv'));
    },

    // Repository Patient
    PatientRepositoryInterface::class => function (ContainerInterface $c) {
    return new PDOPatientRepository($c->get('db.toubipat'));
    },

    // Repository Auth
    AuthRepositoryInterface::class => function (ContainerInterface $c) {
    return new PDOAuthReposiroty($c->get('db.toubiauth'));
    },
];


