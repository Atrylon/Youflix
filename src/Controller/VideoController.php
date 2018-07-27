<?php

namespace App\Controller;

use App\Entity\Video;
use App\Form\AddVideoType;
use App\Repository\VideoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class VideoController extends Controller
{
    /**
     * @Route("/addVideo", name="addVideo")
     */
    public function index(Request $request, VideoRepository $videoRepository)
    {
        $video = new Video();
        $form = $this->createForm(AddVideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $video->setUser($this->getUser());
            $entityManager->persist($video);
            $entityManager->flush();
            $this->addFlash('notice', 'Vidéo ajoutée!');
            return $this->redirectToRoute('home');
        }

        return $this->render('video/addVideo.html.twig', [
            'formVideo' => $form->createView(),
        ]);
    }
}
