<?php
/**
 * Created by PhpStorm.
 * User: adelaunay2017
 * Date: 12/07/2018
 * Time: 09:10
 */

namespace App\Controller;


use App\Entity\Profile;
use App\Form\ProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends Controller
{
    /**
     * Affiche la page add-idea du site
     * @Route ("/profile/add", name="profile_add")
     */
    public function addProfile(Request $request){

        $profile = new Profile();
        $profileForm = $this->CreateForm(ProfileType::class);

        $profileForm->handleRequest($request);
        if($profileForm->isSubmitted() && $profileForm->isValid()) {

            $profile->setFirstname($profile->getFirstname());
            $profile->setLastname($profile->getLastname());
            $profile->setPicture($profile->getPicture());

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($profile);

            $entityManager->flush();

            return $this->redirectToRoute("profile/add.html.twig");
        }

        return $this->render('profile/add.html.twig', [
            "profileForm" => $profile->createView(),
        ]);
    }
}