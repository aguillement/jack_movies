<?php
/**
 * Created by PhpStorm.
 * User: adelaunay2017
 * Date: 12/07/2018
 * Time: 11:15.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function getHome()
    {
        return $this->render('base.html.twig');
    }

    /**
     * @Route("/404", name="404")
     */
    public function get404()
    {
        return $this->render('error/404.html.twig');
    }

    /**
     * @Route("/accessDenied", name="access_denied")
     */
    public function accessDenied()
    {
        return $this->redirectToRoute('404');
    }
}
