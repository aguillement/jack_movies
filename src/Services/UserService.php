<?php
/**
 * Created by PhpStorm.
 * User: agauvrit2017
 * Date: 17/07/2018
 * Time: 10:22
 */

namespace App\Services;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class UserService
{
    private $_repository;
    private $_entityManager;

    public function __construct(ObjectRepository $repesitory,EntityManager  $entityManager)
    {
        $this->_repository = $repesitory;
        $this->_entityManager = $entityManager;
    }

    /**
     * @param $user
     * @return float|int
     */
    public function getRating($user){
        $history = $user->getHistory();
        $historyMovies = $history->getHistoryMovies();
        $noteTotal = 0;
        foreach ($historyMovies as $row) {
            $noteTotal += $row->getNote();
        }
        $noteTotal = round($noteTotal / count($historyMovies));

        return $noteTotal;
    }

    /**
     * @param $user
     * @return int
     */
    public function getNumberFilmSeen($user){
        $history = $user->getHistory();
        $historyMovies = $history->getHistoryMovies();

        return count($historyMovies);
    }

    /**
     * @param $user
     * @return int
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getNumberTimeSeen($user){
        $history = $user->getHistory();
        $historyMovies = $history->getHistoryMovies();
        $time = 0;
        foreach ($historyMovies as $row) {
            $movie = $this->_entityManager->getRepository("App\Entity\Movie")->createQueryBuilder('m')
                ->where('m.id = :id')
                ->setParameter('id', $row->getMovie()->getId())
                ->getQuery()
                ->getSingleResult();
            $time += $movie->getDuration();
        }
        return $time;
    }

    /**
     * @param $user
     * @return array
     */
    public function getAllstat($user){
        $timeSeen = 0;
        try {
            $timeSeen = $this->getNumberTimeSeen($user);
        } catch (NoResultException $e) {

        } catch (NonUniqueResultException $e) {

        }
        $stats = array(
            "stats_history" => $this->_repository->getStatHistory($user->getId()),
            "stats_watchlist" => $this->_repository->getStatWatchlist($user->getId()),
            "rating" => $this->getRating($user),
            "numberFilmSeen" => $this->getNumberFilmSeen($user),
            "timeSeen" => $timeSeen
        );

        return $stats;
    }
}