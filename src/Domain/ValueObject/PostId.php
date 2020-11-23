<?php

namespace App\Domain\ValueObject;

class PostId
{
    /**
     * @todo add validation in value objects, change to int
     */
    private string $postId;

    public function __construct(string $postId)
    {
        $this->postId = $postId;
    }

    public function toString(): string
    {
        return $this->postId;
    }
}