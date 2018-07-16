<?php
/**
 * Created by PhpStorm.
 * User: adelaunay2017
 * Date: 12/07/2018
 * Time: 08:54
 */

namespace App\Controller;

use App\Entity\History;
use App\Entity\Profile;
use App\Entity\Watchlist;
use App\Form\RightsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
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
            // récupération des données du formulaire
            $user = $form->getData();
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $entityManager = $this->getDoctrine()->getManager();

            //Create profile
            $profile = new Profile();
            $entityManager->persist($profile);
            $entityManager->flush();

            //Create user
            $user->setRoles(['ROLE_USER']);
            $user->setProfile($profile);
            $entityManager->persist($user);
            $entityManager->flush();

            //Create watchlist
            $watchlist = new Watchlist();
            $watchlist->setUser($user);
            $watchlist->setDateCreate(new \DateTime());
            $entityManager->persist($watchlist);
            $entityManager->flush();

            //Create history
            $history = new History();
            $history->setUser($user);
            $history->setDate(new \DateTime());
            $entityManager->persist($history);
            $entityManager->flush();

            // auto connect
            $token = new UsernamePasswordToken(
                $user,
                $user->getPassword(),
                'main',
                $user->getRoles()
            );

            $this->get('security.token_storage')->setToken($token);
            $this->get('session')->set('_security_main', serialize($token));

            $this->addFlash('success', 'You are now successfully registered!');

            return $this->redirect($this->generateUrl('movies'));
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
            ->add('_username')
            ->add('_password', PasswordType::class)
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
    public function logout()
    {
    }

    /**
     * @Route("/deleteAccount", name="deleteAccount")
     */
    public function deleteAccount()
    {
        $user = $this->getUser();

        $this->get('security.token_storage')->setToken(null);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', 'You have delete your account!');

        return $this->redirect($this->generateUrl('home'));
    }

    /**
     * @Route("/user/modifyrights", name="modify_user_rights")
     */
    public function setRights(Request $request)
    {
        $id = 1;
        $rep = $this->getDoctrine()->getRepository(User::class);
        $user = $rep->find($id);
        $form = $this->CreateForm(RightsType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($user);

            dump($user);

            $entityManager->flush();

            $this->redirect('movies');
        }

        return $this->render('User/rights.html.twig', [
            'rightsForm' => $form->createView()
        ]);
    }
}
