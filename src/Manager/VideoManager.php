<?php
/**
 * Created by PhpStorm.
 * User: beren
 * Date: 28/07/2018
 * Time: 15:22
 */

namespace App\Manager;


use App\Entity\Video;
use App\Repository\VideoRepository;

class VideoManager
{
    private $videoRepository;

    public function __construct(VideoRepository $videoRepository)
    {
        $this->videoRepository = $videoRepository;
    }

    public function getVideoByTitle(string $title): ?Video
    {
        return $this->videoRepository->findOneBy(['title' => $title]);
    }

    public function getVideosByTitle(string $title): ?array
    {
        return $this->videoRepository->findBy(['title'=> $title]);
    }

}