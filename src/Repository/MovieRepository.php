<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
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
     * Our new getAllPosts() method
     *
     * 1. Create & pass query to paginate method
     * 2. Paginate will return a `\Doctrine\ORM\Tools\Pagination\Paginator` object
     * 3. Return that object to the controller
     *
     * @param integer $currentPage The current page (passed from controller)
     *
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    public function getAllPosts($currentPage = 1)
    {
        // Create our query
        $query = $this->createQueryBuilder('m')
            ->orderBy('m.id', 'DESC')
            ->getQuery();

        $paginator = $this->paginate($query, $currentPage);

        return $paginator;
    }

    /**
     * @return Movie[] Return an array of Movie objects
     */
    public function searchTitle($value){
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
            ->getQuery();

        return $movies;
    }

    /**
     * @param $dql
     * @param int $page
     * @param int $limit
     * @return Paginator
     */
    public function paginate($dql, $page = 1, $limit = 9)
    {
        $paginator = new Paginator($dql);

        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1))// Offset
            ->setMaxResults($limit); // Limit

        return $paginator;
    }
}
