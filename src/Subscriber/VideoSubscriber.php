<?php
/**
 * Created by PhpStorm.
 * User: beren
 * Date: 28/07/2018
 * Time: 15:49
 */

namespace App\Subscriber;

use App\Event\VideoAddedEvent;
use App\Event\VideoDeletedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class VideoSubscriber implements EventSubscriberInterface
{
    private $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            VideoAddedEvent::NAME => 'onVideoAddedEvent',
            VideoDeletedEvent::NAME => 'onVideoDeletedEvent',
        ];
    }

    public function onVideoAddedEvent(VideoAddedEvent $videoAddedEvent){
        $video = $videoAddedEvent->getVideo();
        $this->logger->info(sprintf('La video %s a été ajoutée par l\'utilisateur %s' ,$video->getTitle(), $video->getUser()->getEmail()));

    }

    public function onVideoDeletedEvent(VideoDeletedEvent $videoDeletedEvent){
        $video = $videoDeletedEvent->getVideo();
        $this->logger->info(sprintf('La video %s de l\'utilisateur %s a été supprimée' ,$video->getTitle(), $video->getUser()->getEmail()));

    }

}