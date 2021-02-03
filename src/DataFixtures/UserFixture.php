<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();

        $user->setEmail('test@example.com');
        $user->setRoles(['1']);

        $password = $this->encoder->encodePassword($user, 'pass_1234');
        $user->setPassword($password);

        $manager->persist($user);

        $manager->flush();
    }
}
