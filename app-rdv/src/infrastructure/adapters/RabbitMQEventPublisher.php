<?php

namespace toubilib\infra\adapters;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use toubilib\core\application\ports\spi\EventPublisherInterface;

class RabbitMQEventPublisher implements EventPublisherInterface
{
    private AMQPStreamConnection $connection;
    private string $exchangeName;
    private string $host;
    private int $port;
    private string $user;
    private string $password;
    private string $vhost;

    public function __construct(
        string $host = 'rabbitmq',
        int $port = 5672,
        string $user = 'toubi',
        string $password = 'toubi',
        string $vhost = '/',
        string $exchangeName = 'rdv.events'
    ) {
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->password = $password;
        $this->vhost = $vhost;
        $this->exchangeName = $exchangeName;
    }

    private function getConnection(): AMQPStreamConnection
    {
        if (!isset($this->connection)) {
            $this->connection = new AMQPStreamConnection(
                $this->host,
                $this->port,
                $this->user,
                $this->password,
                $this->vhost
            );
        }
        return $this->connection;
    }

    public function publishRdvEvent(string $event, array $rdvData, array $destinataires): void
    {
        $connection = $this->getConnection();
        $channel = $connection->channel();

        try {
            $channel->exchange_declare($this->exchangeName, AMQPExchangeType::TOPIC, false, true, false);

            $routingKey = 'rdv.' . strtolower($event);

            $messageData = [
                'event' => $event,
                'rdv' => $rdvData,
                'destinataires' => $destinataires,
                'timestamp' => date('Y-m-d H:i:s')
            ];

            $messageBody = json_encode($messageData, JSON_UNESCAPED_UNICODE);
            $message = new AMQPMessage($messageBody, [
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
                'content_type' => 'application/json'
            ]);

            $channel->basic_publish($message, $this->exchangeName, $routingKey);
        } finally {
            $channel->close();
        }
    }

    public function __destruct()
    {
        if (isset($this->connection)) {
            $this->connection->close();
        }
    }
}
