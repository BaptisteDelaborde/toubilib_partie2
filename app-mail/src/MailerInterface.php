<?php

namespace AppMail;

interface MailerInterface
{
    public function send(string $to, string $subject, string $text): void;
}

