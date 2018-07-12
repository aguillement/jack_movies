<?php
/**
 * Created by PhpStorm.
 * User: adelaunay2017
 * Date: 12/07/2018
 * Time: 14:42
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class HistoryController extends Controller
{
    /**
     * @Route("/history", name="history")
     */
    public function getHistory(){

        $history = $this->getUser()->getHistory();
        $history->getHistoryMovies();

        dump($history);

        return $this->render('History/history.html.twig',compact("history"));
    }

}