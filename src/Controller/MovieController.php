<?php

namespace App\Controller;

use App\Entity\HistoryMovie;
use App\Entity\Category;
use App\Entity\Movie;
use App\Form\MovieType;
use App\Form\RateMovieFormType;
use App\Repository\MovieRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Services\MovieService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MovieController extends Controller
{
    /**
     * @Route("/movies/{page}", name="movies")
     */
    public function movies(Request $request, $page = 1)
    {
        $rep = $this->getDoctrine()->getRepository(Movie::class);
        $movies = $rep->findAll();
        $entityManager = $this->getDoctrine()->getManager();

        //Form use for filter categories
        $formCategory = $this->createFormBuilder()
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'libelle',
                'multiple' => false,
            ])
            ->getForm();

        $formCategory->handleRequest($request);

        if ($formCategory->isSubmitted() && $formCategory->isValid()) {
            $categories = $formCategory->getData();

            //filter movies by categories
            $selectedCategory = array_values($categories)[0];
            $id = $selectedCategory->getId();

            $query = $entityManager->getRepository("App\Entity\Movie")->createQueryBuilder('m')
                ->innerjoin('m.categories', 'c')->addSelect('c')
                ->where('c.id = :id')
                ->setParameter('id', $id)
                ->getQuery();

            $movies = $query->getResult();
            foreach ($movies as $movie) {
                $pathImage = $movie->getPicture();
                $movie->setPathPicture($pathImage);
            }

            //pagination
            /** @var MovieRepository $repository */
            $repository = $this->getDoctrine()->getRepository(Movie::class);
            $paginator =  $repository->paginate($query, $page);
            $maxPages = ceil($paginator->count() / $limit);
            $thisPage = $page;

            foreach ($movies as $movie) {
                $movie->getCategories();

                $pathImage = $movie->getPicture();
                $movie->setPathPicture($pathImage);
            }

            return $this->render('movie/index.html.twig',[
                'movies' => $paginator,
                'formCategory' => $formCategory->createView(),
                'maxPages' => $maxPages,
                'thisPage' => $thisPage,
            ]);
        }
        foreach ($movies as $movie) {
            $pathImage = $movie->getPicture();
            $movie->setPathPicture($pathImage);
        }

        //pagination
        /** @var MovieRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Movie::class);
        $paginator =  $repository->getAllPosts($page);
        $limit = 5;
        $maxPages = ceil($paginator->count() / $limit);
        $thisPage = $page;

        return $this->render('movie/index.html.twig',[
            'movies' => $paginator,
            'formCategory' => $formCategory->createView(),
            'maxPages' => $maxPages,
            'thisPage' => $thisPage,
            ]);
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
                'id' => $id,
            ], 307);
        }

        $rep = $this->getDoctrine()->getRepository(Movie::class);
        $movie = $rep->find($id);

        return $this->render('movie/movie.html.twig', [
            'movie' => $movie,
            'rateForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/movies/search", name="search_movie")
     */
    public function searchMovie(Request $request, MovieService $movieService)
    {

        if ('POST' === $request->getMethod()) {
            $movies = $movieService->getMovies($request->get('search'));
            foreach ($movies as $movie) {
                $pathImage = $movie->getPicture();
                $movie->setPathPicture($pathImage);
            }
        }

        return $this->render('movie/index.html.twig', compact('movies'));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/movies/add", name="add_movie")
     */
    public function addMovie(Request $request)
    {
        $movie = new Movie();

        $form = $this->CreateForm(MovieType::class, $movie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
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
                'addMovieForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/movie/modify/{id}", name="modify_movie")
     */
    public function modifyMovie(Request $request, $id)
    {
        $rep = $this->getDoctrine()->getRepository(Movie::class);
        $movie = $rep->find($id);

        $form = $this->CreateForm(MovieType::class, $movie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
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
            'modifyMovieForm' => $form->createView(),
        ]);
    }
}
