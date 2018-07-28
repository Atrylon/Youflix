<?php
/**
 * Created by PhpStorm.
 * User: beren
 * Date: 28/07/2018
 * Time: 15:42
 */

namespace App\Subscriber;


use App\Event\UserDeletedEvent;
use App\Event\UserRegisteredEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserSubscriber implements EventSubscriberInterface
{
    private $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            UserRegisteredEvent::NAME => 'onUserRegisteredEvent',
            UserDeletedEvent::NAME => 'onUserDeletedEvent',
        ];
    }

    public function onUserRegisteredEvent(UserRegisteredEvent $userRegisteredEvent){
        $user = $userRegisteredEvent->getUser();
        $this->logger->info(sprintf('L\'utilisateur %s - %s vient de s\'inscrire' ,$user->getId(), $user->getEmail()));

    }

    public function onUserDeletedEvent(UserDeletedEvent $userDeletedEvent){
        $user = $userDeletedEvent->getUser();
        $this->logger->info(sprintf('L\'utilisateur %s - %s vient d`\'être supprimé' ,$user->getId(), $user->getEmail()));

    }
}