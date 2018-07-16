<?php

namespace App\Controller;

use App\Entity\Movie;
use App\IMDbapi;
use App\Form\MovieType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\DateTime;

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

            $pathImage = $movie->getPicture();
            $movie->setPathPicture($pathImage);
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
                $imdb = new IMDbapi('S53G64acNvXFgLfyBFpdEYYJKBcFoR');
                $data = $imdb->title($search,'json');
                $data = json_decode($data);

                $newMovie = new Movie();
                $newMovie->setTitle($data->{'title'});
                $newMovie->setDirector($data->{'director'});

                // Format release date
                $date = explode(" ", $data->{'released'});
                dump($data);
                $date = $date[2]."-".$date[1]."-".$date[0];
                dump($data);
                $newMovie->setReleaseDate(\DateTime::createFromFormat('Y-M-d', $date));

                preg_match_all('!\d+!', $data->{'runtime'}, $duration);
                $newMovie->setDuration($duration[0][0]);
                $newMovie->setSynopsis($data->{'plot'});

                $newMovie->setPicture($data->{'poster'});

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($newMovie);
                $entityManager->flush();

                return $this->render('movie/movie.html.twig', [
                    "movie" => $newMovie,
                ]);
            }

            foreach($movies as $movie) {
                $pathImage = $movie->getPicture();
                $movie->setPathPicture($pathImage);
            }
        }

        return $this->render('movie/index.html.twig',compact("movies"));
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
