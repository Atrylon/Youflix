<?php
/**
 * Created by PhpStorm.
 * User: beren
 * Date: 28/07/2018
 * Time: 16:03
 */

namespace App\Event;


use App\Entity\Video;
use Symfony\Component\EventDispatcher\Event;

class VideoDeletedEvent extends Event
{
    const NAME = "video.added";
    protected $video;

    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    public function getVideo(): Video
    {
        return $this->video;
    }
}