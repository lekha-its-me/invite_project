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

    /**
     * @return Response
     */
    public function index()
    {
        $guests = $this->guestRepository->findALl();
        return $this->render('guests/index.html.twig',[
            'guests' => $guests
        ]);
    }

    /**
     * @param Request $request
     * @param SendEmailService $emailService
     * @param GuestRepositoryInterface $guestRepository
     * @param Swift_Mailer $mailer
     * @return Response
     */
    public function send(Request $request, SendEmailService $emailService, GuestRepositoryInterface $guestRepository, Swift_Mailer $mailer)
    {
        $recipients = explode(',', $request->get('recipients'));
        $subject = $request->get('subject');
        $letterBody = $request->get('letterBody');

        for($i=0; $i<count($recipients); $i++)
        {
            $addresser = $guestRepository->find($recipients[$i]);

            $qr = new QrCode($request->getScheme() . '://' . $request->getHttpHost() . '/guest/'.$addresser->getHash());
            header('Content-Type: '.$qr->getContentType());
            $qr->writeFile(getcwd() . '/qr/'.$addresser->getHash().'.png');
            $qrPictureAddress = $request->getScheme() . '://' . $request->getHttpHost() . '/qr/'.$addresser->getHash().'.png';

            $result = $emailService->send($subject, $addresser->getEmail(), $letterBody, $qrPictureAddress, $mailer);
        }

        if (!$result)
        {
            return new Response('error', 400);
        }

        return new Response('ok', 200);
    }
}
