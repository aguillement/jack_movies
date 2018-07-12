<?php

namespace App\Controller;

use App\Entity\Movie;
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
        return $this->render('movie/index.html.twig',compact("movies"));
    }

    /**
     * @Route("/movie/{id}", name="movie")
     */
    public function movie($id)
    {
        $rep = $this->getDoctrine()->getRepository(Movie::class);
        $movie = $rep->find($id);
        dump($movie);
        return $this->render('movie/movie.html.twig',compact("movie"));
    }

    /**
     * @Route("/movie/search", name="search_movie")
     */
    public function searchMovie($search){

        $entityManager = $this->getDoctrine()->getManager();

        $movie = $entityManager->getRepository("Movie")->createQueryBuilder('m')
            ->where('m.title LIKE :product')
            ->setParameter('product', ''.$search .'%'.'')
            ->getQuery()
            ->getResult();

        dump($movie);

        return $this->render('movie/index.html.twig',compact("movie"));
    }
}
