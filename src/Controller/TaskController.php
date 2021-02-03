<?php

namespace App\Controller;

use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends AbstractController
{

    public function index(TaskService $taskService): JsonResponse
    {
        $tasks = $taskService->list();

        return new JsonResponse($tasks);
    }

    /**
     * @param TaskService $taskService
     * @param Request     $request
     *
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \App\Exceptions\ValidationException
     */
    public function create(TaskService $taskService, Request $request): JsonResponse
    {
        // Validate and create the project
        $task = $taskService->createTask($request);

        $task = $taskService->saveTask($task);

        return new JsonResponse(['task_id' => $task->getId()]);
    }

    /**
     * @param TaskService $taskService
     * @param Request     $request
     * @param int         $taskId
     *
     * @return JsonResponse
     * @throws \App\Exceptions\ValidationException
     * @throws \Doctrine\ORM\ORMException
     */
    public function edit(TaskService $taskService, Request $request, int $taskId): JsonResponse
    {
        $task = $taskService->getTask($taskId);

        // Validate and update the project
        $taskService->editTask($request, $task);

        return new JsonResponse(['success' => 'true']);
    }

    /**
     * @param TaskService    $taskService
     * @param                $taskId
     *
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     */
    public function delete(TaskService $taskService, $taskId): Response
    {
        $taskService->removeTask($taskId);

        return new Response('', 204);
    }

    /**
     * @param TaskService $taskService
     * @param int         $projectId
     *
     * @return Response
     */
    public function listByProject(TaskService $taskService, int $projectId): Response
    {
        // Get the tasks
        $tasks = $taskService->getTasksByTaskId($projectId);

        return new JsonResponse($tasks);
    }
}
