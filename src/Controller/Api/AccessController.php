<?php

namespace App\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use App\Repository\GuestRepository;
use Symfony\Component\HttpFoundation\Response;

class AccessController
{
    public function __construct(GuestRepository $guestRepository)
    {
        $this->guestRepository = $guestRepository;
    }

    /**
     * @Rest\Get("/guest/{hash}")
     */
    public function getGuest(string $hash): Response
    {
        $guest = $this->guestRepository->findOneByHash($hash);
        if(!is_null($guest))
        {
            $this->guestRepository->setIsComes($guest->getId());
            return new Response('Билет принят', 200);
        }
        else
        {
            return new Response('Неверный билет', 400);
        }

    }
}
