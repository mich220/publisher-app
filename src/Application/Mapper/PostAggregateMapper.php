<?php

namespace App\Application\Mapper;

use App\Domain\Aggregates\PostAggregate;
use App\Domain\Collections\CommentsCollection;
use App\Domain\Entity\Post;
use App\Infrastructure\TransferObject\DbResult;
use App\UI\TransferObject\PostDTO;

// @todo mapper per layer?
class PostAggregateMapper
{
    public static function mapAggregateToPostDto(PostAggregate $aggregate): PostDTO
    {
        $dto = new PostDTO();
        $dto->post = $aggregate->getPost();
        $dto->commentsCollection = $aggregate->getComments();

        return $dto;
    }

    public static function mapDbResultToAggregate(DbResult $post): PostAggregate
    {
        if ($post->hasRelation('comments')) {
            $commentsCollection = CommentsCollection::createFromDbResult($post->getRelation('comments'));
        } else {
            $commentsCollection = new CommentsCollection();
        }
        $post = Post::createFromArray($post->getData());

        return new PostAggregate($post, $commentsCollection);
    }

    public static function mapDbResultToDTO(DbResult $post): PostDTO
    {
        $dto = new PostDTO();

        if ($post->hasRelation('comments')) {
            $dto->commentsCollection = CommentsCollection::createFromDbResult($post->getRelation('comments'));
        } else {
            $dto->commentsCollection = new CommentsCollection();
        }

        $post = Post::createFromArray($post->getData());
        $dto->post = $post;

        return $dto;
    }
}
