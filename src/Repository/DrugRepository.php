<?php

namespace App\Repository;

use App\Entity\Drug;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Drug|null find($id, $lockMode = null, $lockVersion = null)
 * @method Drug|null findOneBy(array $criteria, array $orderBy = null)
 * @method Drug[]    findAll()
 * @method Drug[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DrugRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Drug::class);
    }

    /**
     * @param $criteria
     * @param $limit
     * @param $offset
     * @return Paginator|mixed
     */
    public function findByCriteria($criteria, $limit, $offset)
    {
        $qb =  $this->createQueryBuilder('d');
        if (!empty($criteria)) {
            $qb
                ->andWhere('d.drugName LIKE :val OR d.bareCode LIKE :val')
                ->setParameter('val', $criteria.'%');
        }

        return $qb
            ->orderBy('d.id', 'ASC')
            ->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }


    /*
    public function findOneBySomeField($value): ?Drug
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
