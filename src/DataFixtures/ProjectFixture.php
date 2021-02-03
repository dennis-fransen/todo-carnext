<?php

namespace App\DataFixtures;

use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProjectFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $project = new Project();
        $project->setTitle('project title');
        $project->setDescription('description 1');

        $manager->persist($project);

        $project = new Project();
        $project->setTitle('project title');
        $project->setDescription('description 2');

        $manager->persist($project);

        $manager->flush();
    }
}
