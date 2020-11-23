<?php

namespace App\Application\Service;

use App\Domain\Cache\ResourceRepository;
use App\Domain\Constant\ResourceStatus;
use App\Domain\ValueObject\TaskId;

class TaskManager
{
    private ResourceRepository $resourceRepository;
    private const DEFAULT_TTL = 120;

    public function __construct(ResourceRepository $resourceRepository)
    {
        $this->resourceRepository = $resourceRepository;
    }

    public function find(TaskId $resourceId)
    {
        return $this->resourceRepository->get($resourceId->toString());
    }

    public function create(): TaskId
    {
        $resourceId = TokenService::generateToken();
        $this->resourceRepository->set($resourceId, ['status' => ResourceStatus::PROCESSING]);

        return new TaskId($resourceId);
    }

    public function finnish(TaskId $resourceId, array $data = [])
    {
        $this->resourceRepository->setEx($resourceId->toString(), [
            'status' => ResourceStatus::SUCCESS,
            'data' => $data
        ], self::DEFAULT_TTL);
    }

    public function fail(TaskId $resourceId, int $code)
    {
        $this->resourceRepository->setEx($resourceId->toString(), ['status' => ResourceStatus::FAILED, 'errorCode' => $code], self::DEFAULT_TTL);
    }
}