<?php

namespace App\Application\Message\Event\Domain;

use App\Domain\ValueObject\TaskId;

class PostTaskFailed
{
    private TaskId $taskId;
    private int $code;

    public function __construct(TaskId $taskId, int $code)
    {
        $this->taskId = $taskId;
        $this->code = $code;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getTaskId(): TaskId
    {
        return $this->taskId;
    }


    public function serialize(): array
    {
        return [
            'task_id' => $this->taskId->toString(),
            'code' => (string)$this->code,
        ];
    }

    public static function deserialize(array $serialized): self
    {
        return new self(new TaskId($serialized['task_id']), (int)$serialized['code']);
    }
}
