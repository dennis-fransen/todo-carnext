<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TaskFixture extends Fixture implements DependentFixtureInterface
{

    /**
     * @var ProjectRepository
     */
    private $projectRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(ProjectRepository $projectRepository, UserRepository $userRepository)
    {
        $this->projectRepository = $projectRepository;
        $this->userRepository    = $userRepository;
    }

    public function load(ObjectManager $manager)
    {
        $task = new Task();
        $task->setTitle('title');
        $task->setDescription('description');
        $task->setStatus(Task::OPEN);
        $task->setProject($this->projectRepository->findAll()[0]);
        $task->setUser($this->userRepository->findAll()[0]);

        $manager->persist($task);

        $task = new Task();
        $task->setTitle('title');
        $task->setDescription('description');
        $task->setStatus(Task::CLOSED);
        $task->setProject($this->projectRepository->findAll()[0]);
        $task->setUser($this->userRepository->findAll()[0]);

        $manager->persist($task);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProjectFixture::class,
            UserFixture::class,
        ];
    }
}
