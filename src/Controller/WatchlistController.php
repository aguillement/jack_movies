<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Watchlist;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WatchlistController extends Controller
{
    /**
     * @Route("/watchlist", name="watchlist")
     */
    public function watchlist()
    {
        $watchlist = $this->getUser()->getWatchlist();
        $watchlist->getMovies();


        return $this->render('watchlist/watchlist.html.twig', [
            "watchlist" => $watchlist,
        ]);
    }

    /**
     * @Route("/watchlist/insert/{id}", name="add_watchlist")
     */
    public function add_watchlist($id){
        $watchlist = $this->getUser()->getWatchlist();
        $watchlist->getMovies();

        $entityManager = $this->getDoctrine()->getManager();
        $rep = $this->getDoctrine()->getRepository(Movie::class);

        $watchlist->addMovie($rep->find($id));

        $entityManager->persist($watchlist);
        $entityManager->flush();

        return $this->render('watchlist/watchlist.html.twig', [
            "watchlist" => $watchlist,
        ]);
    }

    /**
     * @Route("/watchlist/remove/{id}", name="remove_watchlist")
     */
    public function remove_watchlist($id){
        $watchlist = $this->getUser()->getWatchlist();
        $watchlist->getMovies();

        $entityManager = $this->getDoctrine()->getManager();
        $rep = $this->getDoctrine()->getRepository(Movie::class);

        $watchlist->removeMovie($rep->find($id));

        $entityManager->persist($watchlist);
        $entityManager->flush();

        return $this->render('watchlist/watchlist.html.twig', [
            "watchlist" => $watchlist,
        ]);
    }
}
