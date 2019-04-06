<?php

namespace App\Service;

use App\Entity\Guest;
use Doctrine\Common\Persistence\ObjectManager;

class ReadAndSaveDataService
{
    public function __construct(ObjectManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param string $data
     * @return bool
     */
    public function saveData(string $data)
    {
        $dataString = explode(';', $data);
        $guest = new Guest($dataString[0], $dataString[1], $dataString[2]);
        $guest->setHash(sha1($dataString[2]));

        $this->em->persist($guest);
        $this->em->flush();

        return true;
    }
}
