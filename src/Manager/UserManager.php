<?php
/**
 * Created by PhpStorm.
 * User: beren
 * Date: 26/07/2018
 * Time: 09:48
 */

namespace App\Manager;


use App\Entity\User;
use App\Repository\UserRepository;

class UserManager
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUserByEmail(string $email): ?User
    {
        return $this->userRepository->findOneBy(['email' => $email]);
    }

    public function getUsersByFirstname(string $firstName): ?array
    {
        return $this->userRepository->findBy(['firstname'=> $firstName], ['email' => 'ASC']);
    }

    public function getUserCountVideosByEmail(string $email): ?int
    {
        $videos = $this->getUserByEmail($email)->getVideos();
        return sizeof($videos);
    }
}