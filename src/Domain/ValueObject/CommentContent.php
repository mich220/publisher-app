<?php

namespace App\Domain\ValueObject;

class CommentContent
{
    /**
     * @todo add validation in value objects
     */
    private string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function toString(): string
    {
        return $this->content;
    }
}