<?php
/**
 * Created by PhpStorm.
 * User: adelaunay2017
 * Date: 12/07/2018
 * Time: 14:42
 */

namespace App\Controller;

use App\Entity\HistoryMovie;
use App\Entity\Movie;
use App\Form\RateMovieFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HistoryController extends Controller
{
    /**
     * @Route("/history", name="history")
     */
    public function history()
    {
        $em = $this->container->get('doctrine')->getEntityManager();

        $history = $this->getUser()->getHistory();

        $historyMovies = $history->getHistoryMovies();
        foreach ($historyMovies as $row) {
            $movie = $em->getRepository("App\Entity\Movie")->createQueryBuilder('m')
                ->where('m.id = :id')
                ->setParameter('id', $row->getMovie()->getId())
                ->getQuery()
                ->getSingleResult();
            $row->setMovie($movie);
        }

        return $this->render('History/history.html.twig', compact("history"));
    }

    /**
     * @Route("/history/insert/{id}", name="addHistoryRow")
     */
    public function addHistoryRow(Request $request, $id)
    {
        $newRow = new HistoryMovie();
        $form = $this->createForm(RateMovieFormType::class, $newRow);
        $form->handleRequest($request);
        $newRow = $form->getData();

        $history = $this->getUser()->getHistory();

        $entityManager = $this->getDoctrine()->getManager();

        // get the selected film
        $repMovie = $this->getDoctrine()->getRepository(Movie::class);

        // create new history row
        $newRow->setHistory($history);
        $newRow->setMovie($repMovie->find($id));

        $entityManager->persist($newRow);
        $entityManager->flush();

        return $this->redirectToRoute("history");
    }

    /**
     * @Route("/history/remove/{id}", name="removeHistoryRow")
     */
    public function removeHistoryRow($id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Delete history row
        $rep = $this->getDoctrine()->getRepository(HistoryMovie::class);
        $entityManager->remove($rep->find($id));
        $entityManager->flush();

        return $this->redirectToRoute("history");
    }
}
