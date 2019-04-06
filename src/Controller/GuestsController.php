<?php

namespace App\Controller;

use App\Repository\GuestRepositoryInterface;
use Endroid\QrCode\QrCode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\SendEmailService;
use Swift_Mailer;


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

    public function send(Request $request, SendEmailService $emailService, GuestRepositoryInterface $guestRepository, Swift_Mailer $mailer)
    {
        $recipients = $request->get('recipients');
        $subject = $request->get('subject');
        $letterBody = $request->get('letterBody');

        for($i=0; $i<count($recipients); $i++)
        {
            $addresser = $guestRepository->find($recipients[$i]);

            $qr = new QrCode($addresser->getHash());
            header('Content-Type: '.$qr->getContentType());
            $qr->writeFile(getcwd() . '/qr/'.$addresser->getHash().'.png');

            $result = $emailService->send($subject, $addresser->getEmail(), $letterBody, $request->getScheme() . '://' . $request->getHttpHost() . '/qr/'.$addresser->getHash().'.png', $mailer);
        }

        if (!$result)
        {
            return new Response('error', 400);
        }

        return new Response('ok', 200);

    }
}
