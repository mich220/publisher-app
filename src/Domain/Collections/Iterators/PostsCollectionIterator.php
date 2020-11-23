<?php

namespace App\Domain\Collections\Iterators;

use App\Domain\Collections\PostsCollection;

class PostsCollectionIterator implements \Iterator
{
    private array $postsCollection = [];

    private int $position;

    public function __construct(PostsCollection $postsCollection) {
        $this->postsCollection = $postsCollection->getPosts();
    }

    public function rewind() {
        $this->position = 0;
    }

    public function valid(): bool {
        return $this->position < count($this->postsCollection);
    }

    public function key(): int {
        return $this->position;
    }

    public function current() {
        return $this->postsCollection[$this->position];
    }

    public function next() {
        $this->position++;
    }
}