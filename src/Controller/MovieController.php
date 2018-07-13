<?php

namespace App\Controller;

use App\Entity\Movie;
use App\IMDbapi;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MovieController extends Controller
{
    /**
     * @Route("/movies", name="movies")
     */
    public function movies()
    {
        $rep = $this->getDoctrine()->getRepository(Movie::class);
        $movies = $rep->findAll();

        foreach($movies as $movie){
            $movie->getCategories();
        }

        return $this->render('movie/index.html.twig',compact("movies"));
    }

    /**
     * @Route("/movie/{id}", name="movie")
     */
    public function movie($id)
    {
        $rep = $this->getDoctrine()->getRepository(Movie::class);
        $movie = $rep->find($id);
        return $this->render('movie/movie.html.twig', [
            "movie" => $movie,
        ]);
    }

    /**
     * @Route("/movies/search", name="search_movie")
     */
    public function searchMovie(Request $request){

        $em = $this->container->get('doctrine')->getEntityManager();

        if ('POST' === $request->getMethod()) {

            $search = $request->get('search');

            $movies = $em->getRepository("App\Entity\Movie")->createQueryBuilder('m')
                ->where('m.title LIKE :title')
                ->setParameter('title', '%'.$search.'%')
                ->getQuery()
                ->getResult();
            if(empty($movies)){
                $imdb = new IMDbapi('B3achrpjvkBkRsQChsPs5vtgXPHUXd');
                $data = $imdb->title($search,'json');
                $data = json_decode($data);

                $newMovie = new Movie();
                $newMovie->setTitle($data->{'title'});
                $newMovie->setDirector($data->{'director'});
                $newMovie->setReleaseDate(new \DateTime($data->{'year'}."-01-01"));

                preg_match_all('!\d+!', $data->{'runtime'}, $duration);
                $newMovie->setDuration($duration[0][0]);
                $newMovie->setSynopsis($data->{'plot'});

                $newMovie->setPicture($data->{'poster'});
                
            }
            dump($data);
        }

        return $this->render('movie/index.html.twig',compact("movies"));
    }
}
