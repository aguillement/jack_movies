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
     *
     * @return mixed : stats on the categories for the hisotry
     */
    public function getStatHistory($id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = $conn->createQueryBuilder()
            ->select('Count(m.id) AS number','c.id','c.libelle')
            ->from('history','h')
            ->innerJoin('h','history_movie', 'hm','hm.history_id=h.id')
            ->innerJoin('hm','movie', 'm','hm.movie_id=m.id')
            ->innerJoin('m','movie_category', 'mc','mc.movie_id=m.id')
            ->innerJoin('mc','category', 'c','mc.category_id=c.id')
            ->where('h.user_id = ?')
            ->groupBy('c.libelle')
            ->getSQL();

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
     *
     * @return mixed : stats on the categories for the watchlist
     */
    public function getStatWatchlist($id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = $conn->createQueryBuilder()
            ->select('Count(m.id) AS number','c.id','c.libelle')
            ->from('watchlist','w')
            ->innerJoin('w','watchlist_movie', 'wm','wm.watchlist_id=w.id')
            ->innerJoin('wm','movie', 'm','wm.movie_id=m.id')
            ->innerJoin('m','movie_category', 'mc','mc.movie_id=m.id')
            ->innerJoin('mc','category', 'c','mc.category_id=c.id')
            ->where('w.user_id = ?')
            ->groupBy('c.libelle')
            ->getSQL();

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
