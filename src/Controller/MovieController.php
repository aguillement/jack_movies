<?php

namespace App\Controller;

use App\Entity\Movie;
use Symfony\Component\HttpFoundation\Request;
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

            dump($request);

            $movies = $em->getRepository("App\Entity\Movie")->createQueryBuilder('m')
                ->where('m.title LIKE :title')
                ->setParameter('title', '%'.$search.'%')
                ->getQuery()
                ->getResult();

            dump($movies);
        }

        return $this->render('movie/index.html.twig',compact("movies"));
    }
}
