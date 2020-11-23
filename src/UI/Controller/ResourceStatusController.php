<?php

namespace App\UI\Controller;

use App\Application\Service\TaskManager;
use App\Domain\Constant\ResourceStatus;
use App\Domain\ValueObject\TaskId;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class ResourceStatusController extends AbstractController
{
    private TaskManager $taskManager;

    public function __construct(TaskManager $taskManager)
    {
        $this->taskManager = $taskManager;
    }

    /**
     * @Route("/resource/{resourceId}/status", name="resource.status", methods={"GET"})
     */
    public function getStatus(string $resourceId): JsonResponse
    {
        $task = $this->taskManager->find(new TaskId($resourceId));

        if ($task === null) {
            return $this->json([
                'status' => 'gone'
            ], 410);
        }
        $status = $task['status'];

        if ($status === ResourceStatus::SUCCESS) {
            $data = $task['data'];
            return $this->json(
                [
                'status' => 'success',
                'redirect_url' => $data['redirect_url']
                ],
                302,
                [
                    'Location' => $data['redirect_url'],
                    'Retry-After' => 1
                ]
            );
        } else if ($status === ResourceStatus::PROCESSING) {
            return $this->json([
                'status' => 'data processing',
            ], 202);
        } else if ($status === ResourceStatus::NOT_FOUND) {
            return $this->json([
                'status' => 'not found'
            ], 404);
        } else if ($status === ResourceStatus::FAILED) {
            return $this->json([
                'status' => 'failed'
            ], $task['errorCode']);
        } else {
            return $this->json([
                'status' => 'unknown'
            ], 500);
        }
    }
}