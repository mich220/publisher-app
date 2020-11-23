<?php

namespace App\Domain\Collections\Iterators;

use App\Domain\Collections\CommentsCollection;

class CommentsCollectionIterator implements \Iterator
{
    private array $commentsCollection = [];

    private int $position;

    public function __construct(CommentsCollection $commentsCollection) {
        $this->commentsCollection = $commentsCollection->getComments();
    }

    public function rewind() {
        $this->position = 0;
    }

    public function valid(): bool {
        return $this->position < count($this->commentsCollection);
    }

    public function key(): int {
        return $this->position;
    }

    public function current() {
        return $this->commentsCollection[$this->position];
    }

    public function next() {
        $this->position++;
    }
}
