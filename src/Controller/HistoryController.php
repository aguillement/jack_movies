<?php
/**
 * Created by PhpStorm.
 * User: adelaunay2017
 * Date: 12/07/2018
 * Time: 14:42
 */

namespace App\Controller;

use App\Entity\HistoryMovie;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class HistoryController extends Controller
{
    /**
     * @Route("/history", name="history")
     */
    public function history(){

        $em = $this->container->get('doctrine')->getEntityManager();

        $history = $this->getUser()->getHistory();

        $historyMovies = $history->getHistoryMovies();
        foreach ($historyMovies as $row ){
            $movie = $em->getRepository("App\Entity\Movie")->createQueryBuilder('m')
                ->where('m.id = :id')
                ->setParameter('id', $row->getMovie()->getId())
                ->getQuery()
                ->getSingleResult();
            $row->setMovie($movie);
        }

        return $this->render('History/history.html.twig',compact("history"));
    }

}