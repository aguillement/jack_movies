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

        dump($watchlist);

        return $this->render('watchlist/index.html.twig');
    }
}
