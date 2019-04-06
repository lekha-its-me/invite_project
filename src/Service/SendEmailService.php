<?php

namespace App\Service;

use Swift_Mailer;
use Symfony\Component\HttpFoundation\Request;

class SendEmailService implements SendEmailServiceInterface
{
    /**
     * SendEmailService constructor.
     * @param \Twig\Environment $templating
     */

    public function __construct(\Twig\Environment $templating)
    {
        $this->templating = $templating;
    }
    public function send(string $subject, string $recipient, string $body, string $qr, Swift_Mailer $mailer)
    {

        $message = (new \Swift_Message($subject))
            ->setFrom('lekha.baranov@gmail.com')
            ->setTo($recipient)
            ->setBody(
                $this->templating->render(
                    'guests/send.html.twig',
                    [
                        'subject' => $subject,
                        'letterBody' => $body,
                        'qr' =>  $qr,
                    ]
                ),
                'text/html'
            )
        ;

        return $mailer->send($message);
    }
}
