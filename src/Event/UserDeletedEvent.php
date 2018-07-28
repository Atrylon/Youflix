<?php
/**
 * Created by PhpStorm.
 * User: beren
 * Date: 28/07/2018
 * Time: 15:55
 */

namespace App\Event;


use App\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class UserDeletedEvent extends Event
{
    const NAME = "user.deleted";
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }

}