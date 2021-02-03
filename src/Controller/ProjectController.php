<?php

namespace App\Controller;

use App\Service\ProjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectController extends AbstractController
{
    /**
     * @param ProjectService $projectService
     *
     * @return JsonResponse
     */
    public function index(ProjectService $projectService): JsonResponse
    {
        $projects = $projectService->list();

        return new JsonResponse($projects);
    }

    /**
     * @param ProjectService $projectService
     * @param                $projectId
     *
     * @return JsonResponse
     */
    public function show(ProjectService $projectService, $projectId): JsonResponse
    {
        $project = $projectService->getProject($projectId);

        return new JsonResponse($project);
    }

    /**
     * @param ProjectService     $projectService
     * @param Request            $request
     *
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     */
    public function create(ProjectService $projectService, Request $request): JsonResponse
    {
        // Validate and create the project
        $project = $projectService->createProject($request);

        $project = $projectService->saveProject($project);

        return new JsonResponse(['project_id' => $project->getId()]);
    }

    /**
     * @param ProjectService $projectService
     * @param                $projectId
     *
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     */
    public function delete(ProjectService $projectService, $projectId): Response
    {
        $projectService->removeProject($projectId);

        return new Response('', 204);
    }
}
