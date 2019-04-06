<?php

namespace App\Repository;

use App\Entity\Guest;
use App\Service\ReadAndSaveDataService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
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
     * @param ReadAndSaveDataService $readAndSaveDataService
     * @param string $filePath
     * @return \RuntimeException
     */
    public function addGuest(ReadAndSaveDataService $readAndSaveDataService, string $filePath)
    {
        if (file_exists($filePath))
        {
            if (($fp = fopen($filePath, "r")) !== FALSE) {
                while (($row = fgetcsv($fp, null, ",")) !== FALSE) {
                    for($i=0; $i<count($row);$i++)
                    {
                        $readAndSaveDataService->saveData($row[$i]);
                    }
                }
                fclose($fp);
            }
        }
        else
        {
            return new \RuntimeException('error');
        }
    }

    public function setIsComes(int $id): ?Guest
    {
        try {
            $guest = $this->createQueryBuilder('p')
                ->where('p.id = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NotFoundResourceException $e) {
            return null;
        }

        if(!is_null($guest))
        {
            $guest->setIsComes(true);
            $this->entityManager->persist($guest);
            $this->entityManager->flush();
            return $guest;
        }
        return null;
    }
}
