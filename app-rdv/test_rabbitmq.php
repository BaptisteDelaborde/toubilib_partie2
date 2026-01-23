<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;

$host = getenv('RABBITMQ_HOST') ?: 'rabbitmq';
$port = getenv('RABBITMQ_PORT') ?: 5672;
$user = getenv('RABBITMQ_USER') ?: 'toubi';
$password = getenv('RABBITMQ_PASS') ?: 'toubi';
$vhost = getenv('RABBITMQ_VHOST') ?: '/';

$exchangeName = 'rdv.events';
$queueName = 'rdv.notifications';
$routingKey = 'rdv.created';

try {
    echo "Connexion à RabbitMQ...\n";
    $connection = new AMQPStreamConnection($host, $port, $user, $password, $vhost);
    $channel = $connection->channel();
    echo "Connexion reussie\n\n";
    
    echo "Suppression de l'exchange existant '$exchangeName' si present...\n";
    try {
        $channel->exchange_delete($exchangeName);
        echo "Ancien exchange supprime\n\n";
    } catch (\Exception $e) {
        echo "Aucun exchange existant\n\n";
    }
    
    echo "Creation de l'exchange '$exchangeName' (type: topic)...\n";
    $channel->exchange_declare($exchangeName, AMQPExchangeType::TOPIC, false, true, false);
    echo "Exchange cree\n\n";
    
    echo "Suppression de la queue existante '$queueName' si presente...\n";
    try {
        $channel->queue_delete($queueName);
        echo "Ancienne queue supprimee\n\n";
    } catch (\Exception $e) {
        echo "Aucune queue existante\n\n";
    }
    
    echo "Creation de la queue '$queueName'...\n";
    $channel->queue_declare($queueName, false, true, false, false);
    echo "Queue creee\n\n";
    
    echo "Binding de la queue à l'exchange avec la cle '$routingKey'...\n";
    $channel->queue_bind($queueName, $exchangeName, $routingKey);
    echo "Binding cree\n\n";
    
    echo "Preparation du message de test...\n";
    $testMessage = [
        'event' => 'CREATE',
        'rdv_id' => 'test-rdv-123',
        'praticien_id' => 'test-praticien-456',
        'patient_id' => 'test-patient-789',
        'date_heure_debut' => '2025-01-25 10:00:00',
        'destinataires' => [
            [
                'type' => 'praticien',
                'email' => 'praticien@example.com'
            ],
            [
                'type' => 'patient',
                'email' => 'patient@example.com'
            ]
        ],
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    $messageBody = json_encode($testMessage, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $message = new AMQPMessage($messageBody, [
        'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
        'content_type' => 'application/json'
    ]);
    
    echo "Message JSON :\n";
    echo $messageBody . "\n\n";
    
    echo "Envoi du message...\n";
    $channel->basic_publish($message, $exchangeName, $routingKey);
    echo "Message envoye avec succes\n\n";
    
    $channel->close();
    $connection->close();
    
    echo "Test termine avec succes\n";
    echo "Verifiez le message dans RabbitMQ : http://localhost:15672 (user: toubi, pass: toubi)\n";
    
} catch (\Exception $e) {
    echo "ERREUR : " . $e->getMessage() . "\n";
    exit(1);
}
