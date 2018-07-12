<?php

namespace App\Controller;

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
}
