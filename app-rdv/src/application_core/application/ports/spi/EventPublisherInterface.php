<?php

namespace toubilib\core\application\ports\spi;

interface EventPublisherInterface
{
    public function publishRdvEvent(string $event, array $rdvData, array $destinataires): void;
}