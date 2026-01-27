<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$host = 'rabbitmq';
$port = 5672;
$user = 'toubi';
$password = 'toubi';
$vhost = '/';

$exchangeName = 'rdv.events';
$queueName = 'mail.notifications';

echo "[MAIL] Connexion à RabbitMQ...\n";

$connection = new AMQPStreamConnection($host, $port, $user, $password, $vhost);
$channel = $connection->channel();

/*Déclarations idempotentes*/
$channel->exchange_declare($exchangeName, 'fanout', false, true, false);
$channel->queue_declare($queueName, false, true, false, false);
$channel->queue_bind($queueName, $exchangeName);

echo "[MAIL] En attente de messages...\n";

$callback = function (AMQPMessage $msg) {
    echo "\nMAIL NOTIFICATION REÇUE\n";
    echo $msg->body . "\n";
    echo "-------------------------\n";

    $msg->getChannel()->basic_ack($msg->getDeliveryTag());
};

$channel->basic_consume(
    $queueName,
    '',
    false,
    false,
    false,
    false,
    $callback
);

while ($channel->is_consuming()) {
    $channel->wait();
}