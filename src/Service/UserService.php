<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserService
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UserService constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param $id
     *
     * @return User
     */
    public function getUser(int $id): User
    {
        $user = $this->userRepository
            ->find($id);

        // check if the requested user exists
        if (null === $user) {
            throw new NotFoundHttpException('The requested user does not exist');
        }

        return $user;
    }
}