<?php

namespace App\Repository;

use App\Entity\Guest;
use App\Service\ReadAndSaveDataService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

/**
 * @method Guest|null find($id, $lockMode = null, $lockVersion = null)
 * @method Guest|null findOneBy(array $criteria, array $orderBy = null)
 * @method Guest[]    findAll()
 * @method Guest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GuestRepository extends ServiceEntityRepository implements GuestRepositoryInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Guest::class);
    }

    // /**
    //  * @return Guest[] Returns an array of Guest objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Guest
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * @return mixed
     */
    public function findOne()
    {

    }


    public function findAllNotComes()
    {
        try {
            return $this->createQueryBuilder('p')
                ->where('p.is_some != :is_come')
                ->setParameter('is_come', 'true')
                ->getQuery()
                ->getOneOrNullResult();
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
}
