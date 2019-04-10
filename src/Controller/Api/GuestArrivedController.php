<?php

namespace App\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use App\Repository\GuestRepository;
use Symfony\Component\HttpFoundation\Response;

class GuestArrivedController
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
            if($this->guestRepository->hasArrived($guest->getId()) != null)
            {
                return new Response('Билет принят', 200);
            }
            else{
                return new Response('По этому билету уже был вход', 404);
            }
        }
        else
        {
            return new Response('Неверный билет', 400);
        }

    }
}
