<?php

namespace App\Service;

use App\Entity\Project;
use App\Exceptions\ValidationException;
use App\Validator\CreateProjectValidator;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ProjectRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProjectService
{

    private $projectRepository;

    private $validator;

    public function __construct(ProjectRepository $projectRepository, ValidatorInterface $validator)
    {
        $this->projectRepository = $projectRepository;
        $this->validator         = $validator;
    }

    /**
     * @param $id
     *
     * @return Project
     */
    public function getProject(int $id): Project
    {
        $project = $this->projectRepository
            ->find($id);

        // check if the requested project exists
        if (null === $project) {
            throw new NotFoundHttpException('The requested project does not exist');
        }

        return $project;
    }

    /**
     * @param Request $request
     *
     * @throws ValidationFailedException
     */
    public function validateProjectRequest(Request $request): void
    {
        $errors = $this->validator->validate(
            new CreateProjectValidator($request)
        );

        if (0 !== count($errors)) {
            throw new ValidationFailedException($request, $errors);
        }
    }

    /**
     * @param Request $request
     *
     * @return Project
     * @throws ValidationFailedException
     *
     */
    public function createProject(Request $request): Project
    {
        $this->validateProjectRequest($request);

        $project = new Project();

        $project->setTitle($request->get('title'));
        $project->setDescription($request->get('description'));

        return $project;
    }

    /**
     * @param Project $project
     *
     * @return Project
     * @throws \Doctrine\ORM\ORMException
     */
    public function saveProject(Project $project): Project
    {
        return $this->projectRepository->save($project);
    }

    /**
     * @param Project $project
     *
     * @throws ValidationFailedException
     * @throws ValidationException
     */
    private function validateProjectDeletion(Project $project): void
    {
        // Basic check so we dont have orphaned tasks. We don't want to delete them ( assumtion )
        if (false == $project->getTasks()->isEmpty()) {
            throw new ValidationException(['project' => 'projects with tasks cannot be deleted']);
        }
    }

    /**
     * @param int $id
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws ValidationException
     */
    public function removeProject(int $id): void
    {
        $project = $this->getProject($id);
        $this->validateProjectDeletion($project);

        $this->projectRepository->remove($project);
    }

    /**
     * @return array
     */
    public function list(): array
    {
        return $this->projectRepository->findAll();
    }
}