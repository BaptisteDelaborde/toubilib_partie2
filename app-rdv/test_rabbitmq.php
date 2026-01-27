<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;

$host = 'rabbitmq';
$port = 5672;
$user = 'toubi';
$password = 'toubi';
$vhost = '/';

$exchangeName = 'rdv.events';
$queueName = 'mail.notifications';

echo "Connexion à RabbitMQ...\n";

$connection = new AMQPStreamConnection($host, $port, $user, $password, $vhost);
$channel = $connection->channel();


/* Exchange FANOUT*/
$channel->exchange_declare(
    $exchangeName,
    AMQPExchangeType::FANOUT,
    false,
    true,
    false
);

/*Queue mail*/
$channel->queue_declare(
    $queueName,
    false,
    true,
    false,
    false
);

/**

Binding (routing key ignorée en fanout)*/
$channel->queue_bind($queueName, $exchangeName);

$messageData = [
    'event' => 'CREATE',
    'rdv_id' => 'test-rdv-123',
    'praticien_id' => 'test-praticien-456',
    'patient_id' => 'test-patient-789',
    'date_heure_debut' => '2025-01-25 10:00:00',
    'timestamp' => date('Y-m-d H:i:s')
];

$message = new AMQPMessage(
    json_encode($messageData, JSON_PRETTY_PRINT),
    ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
);

echo "Envoi du message sur l'exchange fanout...\n";

$channel->basic_publish($message, $exchangeName);

echo "Message envoyé\n";

$channel->close();
$connection->close();