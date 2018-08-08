<?php

namespace App\Controller;

use App\Entity\Video;
use App\Event\VideoAddedEvent;
use App\Event\VideoDeletedEvent;
use App\Form\AddVideoType;
use App\Form\EditVideoType;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class VideoController extends Controller
{

    //    Ajoute une video (un Titre, une URL, une description) et la lie à l'utilisateur connecté
    /**
     * @Route("/addVideo", name="addVideo")
     */
    public function addVideo(Request $request, EventDispatcherInterface $eventDispatcher)
    {
        $video = new Video();
        $form = $this->createForm(AddVideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $video->setUser($this->getUser());
            $entityManager->persist($video);
            $entityManager->flush();
            $this->addFlash('success', 'Vidéo ajoutée!');
            $event = new VideoAddedEvent($video);
            $eventDispatcher->dispatch(VideoAddedEvent::NAME, $event);
            return $this->redirectToRoute('myVideo');
        }

        return $this->render('video/addVideo.html.twig', [
            'formVideo' => $form->createView(),
        ]);
    }

    //    Récupère la liste des vidéos de l'utilisateur connecté
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

    //    Permet d'éditer les informations de la vidéo dont l'Id est passé en paramètre.
    //    Ne peut afficher que les vidéos appartenant à l'utilisateur connecté, sauf si Admin
    /**
     * @Route("/video/edit/{id}", name="editVideo")
     */
    public function editVideo(Request $request, EntityManagerInterface $entityManager,
                              VideoRepository $videoRepository, int $id){

        $video = $videoRepository->find($id);

        //        Verifie que la video a été ajoutée par l'utilisateur courant et qu'il ne soit pas admin
        if($video->getUser() != $this->getUser() and !in_array('ROLE_ADMIN', $this->getUser()->getRoles())){
            $this->addFlash('warning', 'Vidéo non trouvée!');
            return $this->redirectToRoute('myVideo');
        }

        $form = $this->createForm(EditVideoType::class, $video);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($video);
            $entityManager->flush();
            $this->addFlash('success', 'Changement(s) effectué(s)!');
            return $this->redirectToRoute('myVideo');
        }

        return $this->render('video/editVideo.html.twig', [
            'form' => $form->createView(),
            'video' => $video,
        ]);
    }

    //    Permet de supprimer la vidéo dont l'id est passé en paramètre
    //    Ne peut supprimer que les vidéos appartenant à l'utilisateur connecté, sauf si Admin
    /**
     * @Route("/video/remove/{id}", name="removeVideo")
     */
    public function removeVideo(Video $video, EntityManagerInterface $entityManager, LoggerInterface $logger,
EventDispatcherInterface $eventDispatcher){

        if($video->getUser() != $this->getUser() and !in_array('ROLE_ADMIN', $this->getUser()->getRoles())){
            $this->addFlash('warning', 'Vidéo non trouvée!');
            return $this->redirectToRoute('myVideo');
        }

        if($video->getUser() != $this->getUser()){
            $this->addFlash('warning', 'Vidéo non trouvée!');
            return $this->redirectToRoute('myVideo');
        }
        $event = new VideoDeletedEvent($video);
        $eventDispatcher->dispatch(VideoDeletedEvent::NAME, $event);
        $entityManager->remove($video);
        $entityManager->flush();
        $this->addFlash('success', 'Vidéo supprimée!');
        return $this->redirectToRoute('myVideo');
    }

//    Récupère une liste de toutes les vidéos enregistrées. A destination de l'Admin
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

//    Permet de récupérer la liste des vidéos de l'utilisateur dont l'id est passé en paramètre. A destination de l'Admin
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
