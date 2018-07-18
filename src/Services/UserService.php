<?php
/**
 * Created by PhpStorm.
 * User: agauvrit2017
 * Date: 17/07/2018
 * Time: 10:22.
 */

namespace App\Services;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use App\Entity\Profile;
use App\Entity\Watchlist;
use App\Entity\History;
use Doctrine\ORM\ORMException;

/**
 * Class UserService
 * @package App\Services
 */
class UserService
{
    private $_repository;
    private $_entityManager;

    /**
     * UserService constructor.
     * @param EntityManager $entityManager
     * @param ObjectRepository|null $repesitory
     */
    public function __construct(EntityManager  $entityManager, ObjectRepository $repesitory = null)
    {
        $this->_repository = $repesitory;
        $this->_entityManager = $entityManager;
    }

    /**
     * @param $user
     *
     * @return float|int
     */
    public function getRating($user)
    {
        $history = $user->getHistory();
        $historyMovies = $history->getHistoryMovies();
        $noteTotal = 0;
        foreach ($historyMovies as $row) {
            $noteTotal += $row->getNote();
        }
        if($noteTotal>0){
            $noteTotal = round($noteTotal / count($historyMovies));
        }

        return $noteTotal;
    }

    /**
     * @param $user
     *
     * @return int
     */
    public function getNumberFilmSeen($user)
    {
        $history = $user->getHistory();
        $historyMovies = $history->getHistoryMovies();

        return count($historyMovies);
    }

    /**
     * @param $user
     *
     * @return int
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getNumberTimeSeen($user)
    {
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
     *
     * @return array
     */
    public function getAllstat($user)
    {

        try {
            $timeSeen = $this->getNumberTimeSeen($user);
        } catch (NoResultException $e) {
            $timeSeen = 0;
        } catch (NonUniqueResultException $e) {
            $timeSeen = 0;
        }

        $stats = [
            'stats_history' => $this->_repository->getStatHistory($user->getId()) ?? [],
            'stats_watchlist' => $this->_repository->getStatWatchlist($user->getId()) ?? [],
            'rating' => $this->getRating($user),
            'numberFilmSeen' => $this->getNumberFilmSeen($user),
            'timeSeen' => $timeSeen,
        ];

        return $stats;
    }

    /**
     * @return Profile|null
     * @throws ORMException, OptimisticLockException
     */
    public function createProfile(){
        $profile = new Profile();
        $this->_entityManager->flush();
        $this->_entityManager->persist($profile);

        return $profile;
    }

    /**
     * @param $user
     * @return mixed
     * @throws ORMException, OptimisticLockException
     */
    public function createUser($user){
        $profile = $this->createProfile();
        //Create user
        $user->setRoles(['ROLE_USER']);
        $user->setProfile($profile);

        $this->_entityManager->persist($user);
        $this->_entityManager->flush();

        $this->createWatchlist($user);
        $this->createHistory($user);

        return $user;
    }

    /**
     * @param $user
     * @return Watchlist|null
     * @throws ORMException, OptimisticLockException
     */
    public function createWatchlist($user){
        $watchlist = null;
        $watchlist = new Watchlist();
        $watchlist->setUser($user);
        $watchlist->setDateCreate(new \DateTime());
        $this->_entityManager->persist($watchlist);
        $this->_entityManager->flush();
        return $watchlist;
    }

    /**
     * @param $user
     * @return History|null
     * @throws ORMException, OptimisticLockException
     */
    public function createHistory($user){
        $history = null;
        $history = new History();
        $history->setUser($user);
        $history->setDate(new \DateTime());
        $this->_entityManager->persist($history);
        $this->_entityManager->flush();
        return $history;
    }

    /**
     * @param $user
     */
    public function removeUser($user){
        try {
            $this->_entityManager->remove($user);
            $this->_entityManager->flush();
        } catch (ORMException $e) {

        }
    }
}
