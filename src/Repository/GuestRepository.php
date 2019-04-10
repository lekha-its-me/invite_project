<?php

namespace App\Repository;

use App\Entity\Guest;
use App\Service\ImportGuestListServiceInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * @method Guest|null find($id, $lockMode = null, $lockVersion = null)
 * @method Guest|null findOneBy(array $criteria, array $orderBy = null)
 * @method Guest[]    findAll()
 * @method Guest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GuestRepository extends ServiceEntityRepository implements GuestRepositoryInterface
{
    public function __construct(RegistryInterface $registry, ObjectManager $entityManager)
    {
        parent::__construct($registry, Guest::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @param $hash
     * @return mixed|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByHash(string $hash): ?Guest
    {
        try {
            return $this->createQueryBuilder('p')
                ->where('p.hash = :hash')
                ->setParameter('hash', $hash)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NotFoundResourceException $e) {
            return null;
        }
    }


    /**
     * @return \Doctrine\ORM\Query|null
     */
    public function findAllNotComes()
    {
        try {
            return $this->createQueryBuilder('p')
                ->where('p.is_comes == :is_comes')
                ->setParameter('is_comes', null)
                ->getQuery();
        } catch (NotFoundResourceException $e) {
            return null;
        }
    }

    /**
     * @param ImportGuestListServiceInterface $importGuestListService
     * @param string $filePath
     * @return bool|\RuntimeException
     */
    public function addGuest(ImportGuestListServiceInterface $importGuestListService, string $filePath)
    {
        if (file_exists($filePath))
        {
            $file = new \SplFileObject($filePath);
            $file->setFlags(\SplFileObject::READ_CSV);
            foreach ($file as $row) {
                $importGuestListService->saveData($row[0]);
            }
        }
        else
        {
            return new \RuntimeException('error');
        }

        return true;
    }

    /**
     * @param Guest $guest
     * @return Guest|null
     */
    public function hasArrived(Guest $guest): ?Guest
    {
        if(!$guest->isComes())
        {
            if(!is_null($guest))
            {
                $guest->setIsComes(true);
                $this->entityManager->persist($guest);
                $this->entityManager->flush();
                return $guest;
            }
        }

        return null;
    }

    /**
     * @param Guest $guest
     * @return Response
     */
    public function save(Guest $guest)
    {
        if($this->hasArrived($guest) != null)
        {
            return new Response('Билет принят', 200);
        }
        else{
            return new Response('По этому билету уже был вход', 404);
        }
    }
}
