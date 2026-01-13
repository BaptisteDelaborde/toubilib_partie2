<?php

use Psr\Container\ContainerInterface;

return [
    'db' => [
        'toubiauth' => [
            'driver' => 'pgsql',
            'host' => $_ENV['TOUBIAUTH_DB_HOST'] ?? 'toubiauth.db',
            'port' => $_ENV['TOUBIAUTH_DB_PORT'] ?? 5433,
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

    // Connexion toubiauth
    'db.toubiauth' => function (ContainerInterface $c): PDO {
        $config = $c->get('db')['toubiauth'];
        $options = $c->get('pdo_options');
        $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";
        return new PDO($dsn, $config['user'], $config['password'], $options);
    },

];


