<?php

namespace App\Controller;

use App\Entity\User;
use App\Event\UserDeletedEvent;
use App\Form\EditUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends Controller
{


//    Affiche et permet la modifications des informations d'un utilisateur. A destination de l'Admin
    /**
     * @Route("/user/{id}", name="user_id", requirements={"id"="\d+"})
     */
    public function user(Request $request, UserRepository $userRepository, int $id,
EntityManagerInterface $entityManager){

        $user = $userRepository->find($id);
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Changement(s) effectué(s)!');
            return $this->redirectToRoute('listUser');
        }

        return $this->render('user/userFiche.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }


    /**
     * @Route("/user/remove/{id}", name="user_remove")
     */
    public function remove(User $user, EntityManagerInterface $entityManager, LoggerInterface $logger,
EventDispatcherInterface $eventDispatcher){

        $videos = $user->getVideos();
        foreach($videos as $video){
            $video->setUser(null);
        }

        $event = new UserDeletedEvent($user);
        $eventDispatcher->dispatch(UserDeletedEvent::NAME, $event);
        $entityManager->remove($user);
        $entityManager->flush();
        $this->addFlash('success', 'Utilisateur supprimé!');
        return $this->redirectToRoute('listUser');
    }
}
