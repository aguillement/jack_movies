<?php
/**
 * Created by PhpStorm.
 * User: aguillement2017
 * Date: 17/07/2018
 * Time: 09:59
 */

namespace App\Services;


use App\Entity\Category;
use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class MovieService
 * @package App\Services
 */
class MovieService
{

    private $em;

    /**
     * MovieService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param string $search
     * @return array|\Exception|mixed|\Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getMovies(string $search)
    {
        $movies = $this->getMoviesDB($search);
        if (empty($movies)) {
            $movies = $this->getMoviesAPI($search);
        }
        return $movies;
    }

    /**
     * @param string $search
     * @return mixed
     */
    public function getMoviesDB(string $search)
    {
        $rep = $this->em->getRepository(Movie::class);
        $movies = $rep->searchTitle($search);

        return $movies;
    }

    /**
     * @param string $search
     * @return array|\Exception|mixed|\Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getMoviesAPI(string $search)
    {
        $movieAPI = new MovieAPI();
        $movies = $movieAPI->searchMovie($search);
        $movies = json_decode($movies);
        $movies = $this->recordNewMovies($movies);

        return $movies;
    }

    /**
     * @param $movies
     * @return array
     */
    public function recordNewMovies($movies)
    {

        foreach ($movies as $movie)
        {
            $newMovie = new Movie();
            $newMovie->setTitle($movie->{'title'});
            $newMovie->setDirector($movie->{'director'});
            $newMovie->setDuration($movie->{'duration'});
            $newMovie->setReleaseDate(\DateTime::createFromFormat('Y-m-d', $movie->{'releaseDate'}));
            $newMovie->setSynopsis($movie->{'synopsis'});
            $newMovie->setPicture('http://image.tmdb.org/t/p/w185/'.$movie->{'picture'});

            $newMovie->setVideoName($movie->{'video_name'});
            $newMovie->setVideoKey('https://www.youtube.com/watch?v='.$movie->{'video_key'});

            $newMovie->setVoteAverage($movie->{'vote_average'});
            $newMovie->setVoteCount($movie->{'vote_count'});

            foreach ($movie->{'category'} as $categoryOfMovie) {
                $category = $this->em->getRepository("App\Entity\Category")->findOneBy(['libelle' => $categoryOfMovie]);
                if (!$category) {
                    $category = new Category();
                    $category->setLibelle($categoryOfMovie);
                }
                $newMovie->addCategory($category);
            }

            $this->em->persist($newMovie);
            $this->em->flush();
            $newMovie->setId($newMovie->getId());
            $newMoviesList[] = $newMovie;
        }

        return $newMoviesList;
    }
}