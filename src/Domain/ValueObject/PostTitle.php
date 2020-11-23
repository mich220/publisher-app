<?php

namespace App\Domain\ValueObject;

class PostTitle
{
    /**
     * @todo add validation in value objects
     */
    private string $title;

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    /**
     * @deprecated
     * use toString()
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    public function toString(): string
    {
        return $this->title;
    }
}