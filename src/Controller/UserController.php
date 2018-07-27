<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends Controller
{

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
            $this->addFlash('notice', 'Changement(s) effectué(s)!');
            return $this->redirectToRoute('admin');
        }

        return $this->render('user/userFiche.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }


    /**
     * @Route("/user/remove/{id}", name="user_remove")
     */
    public function remove(User $user, EntityManagerInterface $entityManager, LoggerInterface $logger){

        $videos = $user->getVideos();
        foreach($videos as $video){
            $video->setUser(null);
        }

        $logger->info('New User registered now ! User : '.$user->getEmail());
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->redirectToRoute('admin');
    }
}
