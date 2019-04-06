<?php

namespace App\Service;

use Swift_Mailer;
use Symfony\Component\HttpFoundation\Request;

interface SendEmailServiceInterface
{
    public function send(string $subject, string $recipient, string $body, string $qr, Swift_Mailer $mailer);
}
