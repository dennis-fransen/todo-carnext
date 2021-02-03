<?php

namespace App\Service;

use App\Entity\Task;
use App\Exceptions\ValidationException;
use App\Repository\TaskRepository;
use App\Validator\MutateTaskValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaskService
{

    /**
     * @var ProjectService
     */
    private $projectService;

    /**
     * @var TaskRepository
     */
    private $taskRepository;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var UserService
     */
    private $userService;

    public function __construct(
        TaskRepository $taskRepository,
        ValidatorInterface $validator,
        ProjectService $projectService,
        UserService $userService
    )
    {
        $this->userService    = $userService;
        $this->projectService = $projectService;
        $this->taskRepository = $taskRepository;
        $this->validator      = $validator;
    }

    /**
     * @param $id
     *
     * @return Task
     */
    public function getTask(int $id): Task
    {
        $task = $this->taskRepository
            ->find($id);

        // check if the requested project exists
        if (null === $task) {
            throw new NotFoundHttpException('The requested project does not exist');
        }

        return $task;
    }

    /**
     * @param Request $request
     *
     * @throws ValidationFailedException
     */
    public function validateTaskRequest(Request $request): void
    {
        $errors = $this->validator->validate(
            new MutateTaskValidator($request)
        );

        if (0 !== count($errors)) {
            throw new ValidationFailedException($request, $errors);
        }
    }

    /**
     * @param Request $request
     *
     * @return Task
     * @throws ValidationFailedException
     * @throws ValidationException
     *
     */
    public function createTask(Request $request): Task
    {
        $this->validateTaskRequest($request);

        $task = new Task();

        try {
            $project = $this->projectService->getProject($request->get('project'));
        } catch (NotFoundHttpException $exception) {
            throw new ValidationException(['project' => 'Project not found']);
        }

        try {
            $user = $this->userService->getUser($request->get('user'));
        } catch (NotFoundHttpException $exception) {
            throw new ValidationException(['user' => 'User not found']);
        }

        $task->setTitle($request->get('title'));
        $task->setDescription($request->get('description'));
        $task->setStatus($request->get('status'));
        $task->setProject($project);
        $task->setUser($user);

        return $task;
    }

    /**
     * @param Task $task
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function saveTask(Task $task): Task
    {
        return $this->taskRepository->save($task);
    }

    /**
     * @param Request $request
     * @param Task    $task
     *
     * @return Task
     * @throws ValidationException
     * @throws \Doctrine\ORM\ORMException
     */
    public function editTask(Request $request, Task $task): Task
    {
        $this->validateTaskRequest($request);

        try {
            $project = $this->projectService->getProject($request->get('project'));
        } catch (NotFoundHttpException $exception) {
            throw new ValidationException(['project' => 'Project not found']);
        }

        try {
            $user = $this->userService->getUser($request->get('user'));
        } catch (NotFoundHttpException $exception) {
            throw new ValidationException(['user' => 'User not found']);
        }

        $task->setTitle($request->get('title'));
        $task->setDescription($request->get('description'));
        $task->setStatus($request->get('status'));
        $task->setProject($project);
        $task->setUser($user);

        $this->saveTask($task);

        return $task;
    }

    /**
     * @param int $id
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function removeTask(int $id): void
    {
        $task = $this->getTask($id);
        $this->taskRepository->remove($task);
    }

    /**
     * @return array
     */
    public function list(): array
    {
        return $this->taskRepository->findAll();
    }


    /**
     * @param int $projectId
     *
     * @return Task[]
     */
    public
    function getTasksByTaskId(int $projectId): array
    {
        $tasks = $this->taskRepository
            ->findByProjectId($projectId);

        return $tasks;
    }
}