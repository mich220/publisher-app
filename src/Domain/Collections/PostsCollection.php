<?php

namespace App\Domain\Collections;

use App\Domain\Collections\Iterators\PostsCollectionIterator;
use App\Domain\Entity\Post;
use App\Infrastructure\TransferObject\DbResult;
use Traversable;

class PostsCollection implements \Countable, \IteratorAggregate
{
    /**
     * @var Post[]
     */
    private array $posts = [];

    public function add(Post $post): void
    {
        $this->posts[] = $post;
    }

    public function count(): int
    {
        return count($this->posts);
    }

    /**
     * @return Post[]
     */
    public function getPosts(): array
    {
        return $this->posts;
    }

    /**
     * @return PostsCollectionIterator|Traversable
     */
    public function getIterator()
    {
        return new PostsCollectionIterator($this);
    }

    public static function createFromDbResult(DbResult $result): self
    {
        $postsCollection = new self();

        foreach ($result->getData() as $post) {
            if ($post['postId'] === null) {
                break;
            }

            $postsCollection->add(Post::createFromArray($post));
        }

        return $postsCollection;
    }

    public function serialize(): array
    {
        $posts = [];
        foreach ($this->posts as $post) {
            $posts[] = $post->serialize();
        }

        return $posts;
    }
}
