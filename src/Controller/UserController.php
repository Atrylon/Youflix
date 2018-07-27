<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{

    /**
     * @Route("/user/{id}", name="user_id", requirements={"id"="\d+"})
     */
    public function user(UserRepository $userRepository, int $id){

        $user = $userRepository->find($id);


        return $this->render('user/userFiche.html.twig', [
            'user' => $user,
        ]);
    }
}
