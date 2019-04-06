<?php

namespace App\Controller;

use App\Repository\GuestRepositoryInterface;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Swift_Mailer;
use Symfony\Component\HttpFoundation\Response;


class GuestsController extends AbstractController
{
    public function __construct(GuestRepositoryInterface $guestRepository)
    {
        $this->guestRepository = $guestRepository;
    }
    public function index()
    {
        $guests = $this->guestRepository->findAll();
        return $this->render('guests/index.html.twig',[
            'guests' => $guests
        ]);
    }

    public function send(Request $request, Swift_Mailer $mailer)
    {
        $recipients = $request->get('recipients');
        $subject = $request->get('subject');
        $letterBody = $request->get('letterBody');

        $qr = new QrCode('http://ukr.net');
        header('Content-Type: '.$qr->getContentType());
        $qr->writeFile(getcwd() . '/qr/123123123.png');

        $message = (new \Swift_Message($subject))
            ->setFrom('lekha.baranov@gmail.com')
            ->setTo('lekha@ukr.net')
//            ->setBody(
//                $this->renderView(
//                // templates/emails/registration.html.twig
//                    'guests/send.html.twig',
//                    [
//                        'subject' => $subject,
//                        'letterBody' => $letterBody,
//                        'qr' =>  $request->getScheme() . '://' . $request->getHttpHost() . '/qr/123123123.png',
//                    ]
//                ),
//                'text/html'
//            )
        ->setBody('Test')
        ;

        $result = $mailer->send($message);

//        return $this->render('guests/send.html.twig',[
//            'recipients' => $recipients,
//            'subject' => $subject,
//            'letterBody' => $letterBody,
//            'qr' =>  $request->getScheme() . '://' . $request->getHttpHost() . '/qr/123123123.png',
//        ]);
        return new Response($result);
    }
}
