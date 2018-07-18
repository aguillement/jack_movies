<?php
/**
 * Created by PhpStorm.
 * User: adelaunay2017
 * Date: 12/07/2018
 * Time: 09:10.
 */

namespace App\Controller;

use App\Entity\Profile;
use App\Form\ProfileType;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends Controller
{
    /**
     * @Route ("/profile/modify", name="profile_modify")
     */
    public function modifyProfile(Request $request)
    {
        $profile = $this->getUser()->getProfile();
        $form = $this->CreateForm(ProfileType::class, $profile);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $form->get('picture')->getData();
            $fileName = "default_user.png";
            if($file!=null){
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('pictures_profile_directory'), $fileName);
            }
            // updates the 'picture' property to store the PDF file name
            // instead of its contents
            $profile->setPicture($fileName);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($profile);
            $entityManager->flush();

            return $this->redirectToRoute('my_profile');
        }

        return $this->render(
            'profile/modify.html.twig',
            ['addProfileForm' => $form->createView()]
        );
    }

    /**
     * @Route("/profile/myprofile", name="my_profile")
     */
    public function getMyProfile()
    {
        // initialize
        $rep = $this->getDoctrine()->getRepository(Profile::class);
        $service = new UserService($this->container->get('doctrine')->getEntityManager(), $rep);

        // init variables
        $user = $this->getUser();
        $profile = $rep->findOneById($user->getProfile()->getId());
        $stats = $service->getAllstat($user);

        return $this->render('profile/my-profile.html.twig', [
            'user' => $user,
            'pathImage' => 'img/profile/'.$profile->getPicture(),
            'stats' => $stats,
        ]);
    }
}
