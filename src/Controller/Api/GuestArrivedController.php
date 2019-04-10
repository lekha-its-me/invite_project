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
            return $this->guestRepository->save($guest);
        }

        return new Response('Неверный билет', 400);
    }
}
