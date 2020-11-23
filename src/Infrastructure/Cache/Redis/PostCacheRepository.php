<?php

namespace App\Infrastructure\Cache\Redis;

use App\Application\Mapper\PostAggregateMapper;
use App\Domain\Aggregates\PostAggregate;
use App\Domain\Entity\Post;
use App\Domain\Repository\PostAggregateRepository;
use App\Domain\ValueObject\PostId;
use App\Infrastructure\TransferObject\DbResult;
use App\UI\TransferObject\PostDTO;

class PostCacheRepository implements PostAggregateRepository
{
    private RedisClient $client;
    private const PREFIX = 'post.';

    public function __construct(RedisClient $client)
    {
        $this->client = $client;
    }

    public function get(PostId $postId): PostDTO
    {
        $result = $this->client->getClient()->get(self::PREFIX.$postId->toString());

        return PostDTO::deserialize(json_decode($result, true));
    }

    public function set(Post $post): void
    {

    }

    public function save(PostAggregate $post): int
    {
        $postId = $post->getPost()->getId()->toString();

        $dto = PostAggregateMapper::mapAggregateToPostDto($post);

        $this->client->getClient()->set($postId, json_encode($dto->serialize()));

        return (int)$postId;
    }

    public function exists(PostId $postId): bool
    {
        return $this->client->getClient()->exists(self::PREFIX.$postId->toString());
    }

    public function update(PostAggregate $post): void
    {
        // TODO: Implement update() method.
    }

    public function delete(PostId $postId): void
    {
        // TODO: Implement delete() method.
    }

    public function load(PostId $postId, bool $withComments = false): DbResult
    {
        // TODO: Implement load() method.
    }
}