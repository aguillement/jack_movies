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
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class MovieService
{

    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em   = $entityManager;
    }

    public function getMovies(string $search){

        $movies = $this->getMoviesDB($search);

        if(empty($movies)){
            $movies = $this->getMoviesAPI($search);
        }
        return $movies;
    }


    public function getMoviesDB(string $search){
        $rep = $this->em->getRepository(Movie::class);
        $movies = $rep->searchTitle($search);

        return $movies;
    }

    public function getMoviesAPI(string $search){

        $movieAPI = new MovieAPI();
        $movies = $movieAPI->searchMovie($search);
        dump($movies);
        $movies = json_decode($movies);

        $movies = $this->recordNewMovies($movies);
        return $movies;
    }

    public function recordNewMovies($movies){

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

            foreach ($movie->{'category'} as $categoryOfMovie) {
                $category = $this->em->getRepository("App\Entity\Category")->findOneBy(['libelle' => $categoryOfMovie]);
                if(!$category){
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