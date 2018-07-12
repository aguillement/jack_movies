<?php
/**
 * Created by PhpStorm.
 * User: adelaunay2017
 * Date: 12/07/2018
 * Time: 08:54
 */

namespace App\Controller;


use App\Entity\Profile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends Controller
{
    /**
     * @Route("/register", name="user_registration")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $entityManager = $this->getDoctrine()->getManager();

            $profile = new Profile();
            $entityManager->persist($profile);
            //update the profile id
            $entityManager->flush();

            $user->setProfile($profile);
            $entityManager->persist($user);

            dump($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_registration');
        }

        return $this->render(
            'user/register.html.twig',
            array('registerForm' => $form->createView())
        );
    }

    /**
     * @param Request $request
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $form = $this->createFormBuilder()
            ->add('_username', EmailType::class)
            ->add('_password', PasswordType::class)
            ->add('submit', SubmitType::class)
            ->getForm();

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'loginForm'     => $form->createView(),
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(){

    }

}