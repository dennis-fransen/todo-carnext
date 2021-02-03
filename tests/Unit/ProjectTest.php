<?php

namespace App\Tests;

use App\Entity\Project;
use App\Entity\Task;
use App\Exceptions\ValidationException;
use App\Repository\ProjectRepository;
use App\Service\ProjectService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProjectTest extends TestCase
{
    /**
     * @throws ValidationException
     * @throws \Doctrine\ORM\ORMException
     * @doesNotPerformAssertions
     */
    public function testProjectDeletionValidation()
    {
        $project = new Project();

        $projectRepository = $this->createMock(ProjectRepository::class);
        $projectRepository->expects($this->any())
                          ->method('find')
                          ->willReturn($project);

        $validatorMock = $this->createMock(ValidatorInterface::class);

        $projectService = new ProjectService($projectRepository, $validatorMock);
        $projectService->removeProject(2);
    }

    public function testProjectWithTaskDeletionValidation()
    {
        $this->expectException(ValidationException::class);

        $project = new Project();

        $task = new Task();

        $project->addTask($task);

        $projectRepository = $this->createMock(ProjectRepository::class);
        $projectRepository->expects($this->any())
                          ->method('find')
                          ->willReturn($project);

        $validatorMock = $this->createMock(ValidatorInterface::class);

        $projectService = new ProjectService($projectRepository, $validatorMock);

        $projectService->removeProject(1);

    }

    public function testProjectDataValidation()
    {
        $this->expectException(ValidationFailedException::class);

        $projectRepository = $this->createMock(ProjectRepository::class);

        $violation = new ConstraintViolation(
            'no title provided',
            '', [], '', '', ''
        );

        $validatorMock = $this->createMock(ValidatorInterface::class);
        $validatorMock->expects($this->once())->method('validate')
                      ->willReturn(new ConstraintViolationList([$violation]));

        $request = new Request([], [json_encode(['title' => 'test'])], [], [], [], [], []);

        $projectService = new ProjectService($projectRepository, $validatorMock);
        $projectService->createProject($request);

    }

    public function testProjectCreation()
    {
        $projectRepository = $this->createMock(ProjectRepository::class);

        $validatorMock = $this->createMock(ValidatorInterface::class);
        $validatorMock->expects($this->once())->method('validate')
                      ->willReturn(new ConstraintViolationList());

        $request = new Request([], [json_encode(['title' => 'test'])], [], [], [], [], []);

        $projectService = new ProjectService($projectRepository, $validatorMock);
        $project        = $projectService->createProject($request);

        $this->assertTrue($project instanceof Project);
    }

}
