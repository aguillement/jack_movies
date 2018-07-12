<?php

namespace App\Repository;

use App\Entity\HistoryMovie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method HistoryMovie|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistoryMovie|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistoryMovie[]    findAll()
 * @method HistoryMovie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoryMovieRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, HistoryMovie::class);
    }

//    /**
//     * @return HistoryMovie[] Returns an array of HistoryMovie objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HistoryMovie
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
