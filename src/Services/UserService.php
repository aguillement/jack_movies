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
    private $repository;
    private $entityManager;

    /**
     * UserService constructor.
     * @param EntityManager $entityManager
     * @param ObjectRepository|null $repesitory
     */
    public function __construct(EntityManager  $entityManager, ObjectRepository $repository = null)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
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
        if($noteTotal!=0){
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
            $movie = $this->entityManager->getRepository("App\Entity\Movie")->createQueryBuilder('m')
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
        $timeSeen = 0;
        try {
            $timeSeen = $this->getNumberTimeSeen($user);
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
        }
        $stats = [
            'stats_history' => $this->repository->getStatHistory($user->getId()),
            'stats_watchlist' => $this->repository->getStatWatchlist($user->getId()),
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
        $this->entityManager->flush();
        $this->entityManager->persist($profile);

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

        $this->entityManager->persist($user);
        $this->entityManager->flush();

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
        $this->entityManager->persist($watchlist);
        $this->entityManager->flush();
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
        $this->entityManager->persist($history);
        $this->entityManager->flush();
        return $history;
    }

    /**
     * @param $user
     */
    public function removeUser($user){
        try {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
        } catch (ORMException $e) {

        }
    }
}
