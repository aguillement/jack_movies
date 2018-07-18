<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    /**
     * @return Movie[] Return an array of Movie objects
     */
    public function searchTitle($value)
    {
        return $this->createQueryBuilder('m')
            ->where('m.title LIKE :title')
            ->setParameter('title', '%'.$value.'%')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Movie[] Return an array of mivie objects with their categories
     */
    public function customFindAll(){
        $movies = $this->findAll();
        foreach ($movies as $movie) {
            $movie->getCategories();
            $movie->setPathPicture($movie->getPicture());
        }
        return $movies;
    }

    /**
     * @param $selectedCategory
     * @return mixed Return an array of movies objects filtred by category
     */
    public function filterMovies($selectedCategory){
        $id = $selectedCategory->getId();
        $movies = $this->createQueryBuilder('m')
            ->innerjoin("m.categories", "c")->addSelect("c")
            ->where("c.id = :id")
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();

        foreach ($movies as $movie) {
            $movie->setPathPicture($movie->getPicture());
        }
        return $movies;
    }

//    /**
//     * @return Movie[] Returns an array of Movie objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Movie
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
