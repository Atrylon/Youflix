<?php

namespace App\Controller;

use App\Entity\Video;
use App\Form\AddVideoType;
use App\Form\EditVideoType;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class VideoController extends Controller
{
    /**
     * @Route("/addVideo", name="addVideo")
     */
    public function addVideo(Request $request, VideoRepository $videoRepository, LoggerInterface $logger)
    {
        $video = new Video();
        $form = $this->createForm(AddVideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $video->setUser($this->getUser());
            $entityManager->persist($video);
            $entityManager->flush();
            $logger->info('New video added now ! User : '.$video->getTitle());
            $this->addFlash('notice', 'Vidéo ajoutée!');
            return $this->redirectToRoute('home');
        }

        return $this->render('video/addVideo.html.twig', [
            'formVideo' => $form->createView(),
        ]);
    }

    /**
     * @Route("/myVideo", name="myVideo")
     */
    public function myVideo(VideoRepository $videoRepository)
    {
        $videos = $videoRepository->findBy(
            ['user' => $this->getUser()->getId()]
        );

        foreach ($videos as $video){
            $embed = explode('=', $video->getUrl());
            $video->setUrl($embed[1]);
        }

        return $this->render('video/myVideo.html.twig', [
            'videos' => $videos,
        ]);
    }

    /**
     * @Route("/video/edit/{id}", name="editVideo")
     */
    public function editVideo(Request $request, EntityManagerInterface $entityManager,
                              VideoRepository $videoRepository, int $id){

        $video = $videoRepository->find($id);
        $form = $this->createForm(EditVideoType::class, $video);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($video);
            $entityManager->flush();
            $this->addFlash('notice', 'Changement(s) effectué(s)!');
            return $this->redirectToRoute('editVideo');
        }

        return $this->render('video/editVideo.html.twig', [
            'form' => $form->createView(),
            'video' => $video,
        ]);
    }

    /**
     * @Route("/video/remove/{id}", name="removeVideo")
     */
    public function removeVideo(Video $video, EntityManagerInterface $entityManager){

        $entityManager->remove($video);
        $entityManager->flush();
        $this->addFlash('notice', 'Vidéo supprimée!');
        return $this->redirectToRoute('myVideo');
    }

    /**
     * @Route("/listVideo", name="listVideo")
     */
    public function listVideoAll(VideoRepository $videoRepository){

        $videos = $videoRepository->findAll();

        foreach ($videos as $video){
            $embed = explode('=', $video->getUrl());
            $video->setUrl($embed[1]);
        }

        return $this->render('video/listVideo.html.twig', [
                'videos' => $videos,
        ]);
    }

    /**
     * @Route("/listVideoByUser/{id}", name="listVideoByUser")
     */
    public function listVideoByUser(VideoRepository $videoRepository, int $id){

        $videos = $videoRepository->findBy(
            ['user' => $id]
        );

        foreach ($videos as $video){
            $embed = explode('=', $video->getUrl());
            $video->setUrl($embed[1]);
        }

        return $this->render('video/listVideo.html.twig', [
            'videos' => $videos,
        ]);
    }
}
