<?php

namespace App\Repository;

use App\Entity\Profile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Profile|null find($id, $lockMode = null, $lockVersion = null)
 * @method Profile|null findOneBy(array $criteria, array $orderBy = null)
 * @method Profile[]    findAll()
 * @method Profile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfileRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Profile::class);
    }

    /**
     * @param $id
     * @return mixed : stats on the categories for the hisotry
     */
    public function getStatHistory($id){
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT Count(movie.id) AS number, category.id, category.libelle
        from history INNER JOIN
            history_movie ON history_movie.history_id=history.id INNER JOIN
            movie ON history_movie.movie_id = movie.id INNER JOIN
            movie_category ON movie_category.movie_id=movie.id INNER JOIN
            category ON movie_category.category_id = category.id
        WHERE history.user_id = ?
        group by category.libelle ";

        $stat = null;

        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();

            $stat = $stmt->fetchAll();
        } catch (DBALException $e) {
        }

        return $stat;
    }

    /**
     * @param $id
     * @return mixed : stats on the categories for the watchlist
     */
    public function getStatWatchlist($id){
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT Count(movie.id) AS number, category.id, category.libelle
        from watchlist INNER JOIN
            watchlist_movie ON watchlist_movie.watchlist_id=watchlist.id INNER JOIN
            movie ON watchlist_movie.movie_id = movie.id INNER JOIN
            movie_category ON movie_category.movie_id=movie.id INNER JOIN
            category ON movie_category.category_id = category.id
        WHERE watchlist.user_id = ?
        group by category.libelle ";

        $stat = null;

        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();

            $stat = $stmt->fetchAll();
        } catch (DBALException $e) {
        }

        return $stat;
    }
}
