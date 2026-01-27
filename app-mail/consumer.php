<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use AppMail\MailerInterface;
use AppMail\MailerService;

$host = 'rabbitmq';
$port = 5672;
$user = 'toubi';
$password = 'toubi';
$vhost = '/';

$exchangeName = 'rdv.events';
$queueName = 'mail.notifications';

echo "[MAIL] Connexion à RabbitMQ\n";

$connection = new AMQPStreamConnection($host, $port, $user, $password, $vhost);
$channel = $connection->channel();

/*Déclarations idempotentes*/
$channel->exchange_declare($exchangeName, 'fanout', false, true, false);
$channel->queue_declare($queueName, false, true, false, false);
$channel->queue_bind($queueName, $exchangeName);

echo "[MAIL] En attente de messages\n";

/* Création du mailer */
function createMailer(): MailerInterface
{
    $impl = getenv('MAILER_IMPL') ?: 'symfony';

    switch ($impl) {
        case 'symfony':
        default:
            return new MailerService();
    }
}

$mailer = createMailer();

$callback = function (AMQPMessage $msg) use ($mailer) {
    echo "\n[MAIL] Message reçu, décodage\n";

    $payload = json_decode($msg->body, true);
    if ($payload === null) {
        echo "[MAIL] Erreur: JSON invalide, ack quand même\n";
        $msg->getChannel()->basic_ack($msg->getDeliveryTag());
        return;
    }

    $event = $payload['event'] ?? 'UNKNOWN';

    $rdv = $payload['rdv'] ?? $payload;

    $destinataires = [];
    if (isset($payload['destinataires']) && is_array($payload['destinataires'])) {
        $destinataires = $payload['destinataires'];
    } else {
        $destinataires[] = [
            'type' => 'debug',
            'email' => 'test@toubilib.local',
        ];
    }

    foreach ($destinataires as $dest) {
        if (!isset($dest['email'])) {
            continue;
        }

        $to = $dest['email'];
        $type = $dest['type'] ?? 'destinataire';

        $subject = "[Toubilib] RDV {$event}";

        $lines = [];
        $lines[] = "Bonjour {$type},";
        $lines[] = "";
        $lines[] = "Un événement concernant un rendez-vous vient de se produire : {$event}";
        $lines[] = "";
        $lines[] = "Détails du rendez-vous :";
        $lines[] = "- ID        : " . ($rdv['id'] ?? $rdv['rdv_id'] ?? 'inconnu');
        $lines[] = "- Praticien : " . ($rdv['praticien_id'] ?? 'inconnu');
        $lines[] = "- Patient   : " . ($rdv['patient_id'] ?? 'inconnu');
        $lines[] = "- Début     : " . ($rdv['date_heure_debut'] ?? 'inconnu');
        if (isset($rdv['motif_visite'])) {
            $lines[] = "- Motif     : " . $rdv['motif_visite'];
        }
        $lines[] = "";
        $lines[] = "Ceci est un message de test envoyé via MailCatcher";

        $body = implode("\n", $lines);

        echo "[MAIL] Envoi d'un mail à {$to}\n";
        try {
            $mailer->send($to, $subject, $body);
            echo "[MAIL] Mail envoyé à {$to}\n";
        } catch (\Throwable $e) {
            echo "[MAIL] Erreur lors de l'envoi du mail à {$to} : " . $e->getMessage() . "\n";
        }
    }

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