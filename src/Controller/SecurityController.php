<?php

namespace App\Controller;

use App\Entity\User;
use App\Event\UserRegisteredEvent;
use App\Form\LoginType;
use App\Form\ProfileUserType;
use App\Form\RegisterUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{

//    Créer un nouvel utilisateur en se basant sur les données du formulaire
//    email et mdp obligatoire
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EventDispatcherInterface $eventDispatcher)
    {
        $user = new User();
        $form = $this->createForm(RegisterUserType::class, $user);
        $form->handleRequest($request);

//        verifie que le formulaire est valide puis crypte le mdp avant de l'ajouter dans le User
        if($form->isSubmitted() && $form->isValid()){
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash( 'success', 'You\'ve been registered succesfully! You can now login');
            $event = new UserRegisteredEvent($user);
            $eventDispatcher->dispatch(UserRegisteredEvent::NAME, $event);
            return $this->redirectToRoute('home');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils){
        $user = new User();
        $form = $this->createForm(LoginType::class, $user);

        return $this->render('security/login.html.twig', [
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'form' => $form->createView(),
        ]);
    }

//    Permet de modifier les données en récupérant le User connecté
    /**
     * @Route("/profile", name="profile")
     */
    public function profile(Request $request, EntityManagerInterface $entityManager){

        $user = $this->getUser();
        $form = $this->createForm(ProfileUserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Changement(s) effectué(s)!');
            return $this->redirectToRoute('profile');
        }

        return $this->render('security/profile.html.twig', [
            'form' => $form->createView()
        ]);
    }

//    Affiche une liste de tous les utilisateurs pour l'administrateur
    /**
     * @Route("/listUser", name="listUser")
     */
    public function admin(UserRepository $userRepository){

        $users = $userRepository->findAll();
        $userList = [];

        foreach($users as $user){
            if($user->getId() !== $this->getUser()->getId()){
                array_push($userList, $user);
            }
        }

        return $this->render('security/listUser.html.twig', [
            'users' => $userList
        ]);
    }
}
