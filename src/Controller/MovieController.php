<?php

namespace App\Controller;

use App\Entity\HistoryMovie;
use App\Entity\Movie;
use App\Form\MovieType;
use App\Form\RateMovieFormType;
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

            $pathImage = "img/movie/" . $movie->getPicture();
            $movie->setPathPicture($pathImage);
        }

        return $this->render('movie/index.html.twig',compact("movies"));
    }

    /**
     * @Route("/movie/{id}", name="movie")
     */
    public function movie(Request $request, $id)
    {
        $newRow = new HistoryMovie();
        $form = $this->createForm(RateMovieFormType::class, $newRow);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('addHistoryRow', [
                'request' => $request,
                'id' => $id
            ], 307);
        }

        $rep = $this->getDoctrine()->getRepository(Movie::class);
        $movie = $rep->find($id);
        return $this->render('movie/movie.html.twig', [
            "movie" => $movie,
            "rateForm" => $form->createView(),
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
            $url = $this->generateUrl('home');
            foreach($movies as $movie) {
                $pathImage = "img/movie/" . $movie->getPicture();
                $movie->setPathPicture($pathImage);
            }

            dump($movies);
        }

        return $this->render('movie/add.html.twig',compact("movies"));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/movies/add", name="add_movie")
     */
    public function addMovie(Request $request){

        $movie = new Movie();

        $form = $this->CreateForm(MovieType::class, $movie);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */

            $file = $form->get('picture')->getData();

            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            $file->move($this->getParameter('pictures_movie_directory'), $fileName);

            // updates the 'picture' property to store the PDF file name
            // instead of its contents
            $movie->setPicture($fileName);

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($movie);

            $entityManager->flush();

            return $this->redirectToRoute('movies');
        }


        return $this->render('movie/add-movie.html.twig', [
                'addMovieForm' => $form->createView()
            ]);
    }

    /**
     * @Route("/movie/modify/{id}", name="modify_movie")
     */
    public function modifyMovie(Request $request, $id){

        $rep = $this->getDoctrine()->getRepository(Movie::class);
        $movie = $rep->find($id);

        $form = $this->CreateForm(MovieType::class, $movie);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */

            $file = $form->get('picture')->getData();

            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            $file->move($this->getParameter('pictures_movie_directory'), $fileName);

            $movie->setPicture($fileName);

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($movie);

            $entityManager->flush();

            return $this->redirectToRoute('movies');
        }


        return $this->render('movie/modify-movie.html.twig', [
            'modifyMovieForm' => $form->createView()
        ]);
    }
}
