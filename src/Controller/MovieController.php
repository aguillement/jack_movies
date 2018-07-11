<?php

namespace App\Controller;

use App\Entity\Movie;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MovieController extends Controller
{
    /**
     * @Route("/films", name="movies")
     */
    public function movies()
    {
        $rep = $this->getDoctrine()->getRepository(Movie::class);
        $movies = $rep->findAll();
        //dump($movies);
        return $this->render('movie/list.html.twig',compact("movies"));
    }

    /**
     * @Route("/film/{id}", name="movie")
     */
    public function movie($id)
    {
        $rep = $this->getDoctrine()->getRepository(MovieRepository::class);
        $movie = $rep->find($id);

        return $this->render('movie/movie.html.twig',compact("movie"));
    }
}
