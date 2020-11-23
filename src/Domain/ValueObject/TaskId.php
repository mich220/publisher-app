<?php

namespace App\Domain\ValueObject;

class TaskId
{
    /**
     * @todo add validation in value objects, change to int
     */
    private string $taskId;

    public function __construct(string $taskId)
    {
        $this->taskId = $taskId;
    }

    public function toString(): string
    {
        return $this->taskId;
    }
}