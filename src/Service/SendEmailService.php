<?php

namespace App\Service;

use Swift_Mailer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class SendEmailService implements SendEmailServiceInterface
{
    /**
     * SendEmailService constructor.
     * @param \Twig\Environment $templating
     * @param ParameterBagInterface $params
     */

    public function __construct(\Twig\Environment $templating, ParameterBagInterface $params)
    {
        $this->templating = $templating;
        $this->params = $params;
    }

    /**
     * @param string $subject
     * @param string $recipient
     * @param string $body
     * @param string $qr
     * @param Swift_Mailer $mailer
     * @return int
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function send(string $subject, string $recipient, string $body, string $qr, Swift_Mailer $mailer)
    {

        $message = (new \Swift_Message($subject))
            ->setFrom($this->params->get('app.mail_from'))
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
