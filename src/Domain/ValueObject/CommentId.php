<?php

namespace App\Domain\ValueObject;

class CommentId
{
    /**
     * @todo add validation in value objects, change into int
     */
    private string $commentId;

    public function __construct(string $commentId)
    {
        $this->commentId = $commentId;
    }

    public function toString(): string
    {
        return $this->commentId;
    }
}