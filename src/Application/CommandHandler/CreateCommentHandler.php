<?php

namespace App\Application\CommandHandler;

use App\Application\Command\Comment\CreateCommentCommand;
use App\Application\Exception\PostNotFoundException;
use App\Application\Mapper\PostAggregateMapper;
use App\Application\Message\Event\Domain\CommentCreated;
use App\Application\Message\Event\Domain\CommentTaskFailed;
use App\Domain\Entity\Comment;
use App\Domain\Repository\PostAggregateRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateCommentHandler implements MessageHandlerInterface
{
    private PostAggregateRepository $postRepository;
    private MessageBusInterface $eventBus;
    private LoggerInterface $logger;

    public function __construct(
        PostAggregateRepository $postRepository,
        MessageBusInterface $eventBus,
        LoggerInterface $logger
    )
    {
        $this->postRepository = $postRepository;
        $this->eventBus = $eventBus;
        $this->logger = $logger;
    }

    public function __invoke(CreateCommentCommand $command)
    {
        $postId = $command->getPostId();
        try {
            if (!$this->postRepository->exists($postId)) {
                throw new PostNotFoundException(sprintf('Post not found, id: %s', $postId->toString()));
            }

            $post = PostAggregateMapper::mapDbResultToAggregate(
                $this->postRepository->load($postId, true)
            );

            $comment = Comment::create(null, $postId, $command->getCommentContent(), $command->getAccessKey());

            $post->publishComment($comment);
            $this->postRepository->update($post);

            $this->eventBus->dispatch(new CommentCreated($postId, $comment->getId(), $command->getTaskId()));
        }
        catch (\Exception $exception) {
            $this->logger->error(sprintf('Error while handling command: %s, error: %s', CreateCommentCommand::class, $exception->getMessage()));
            $this->eventBus->dispatch(new CommentTaskFailed($command->getTaskId(), $exception->getCode()));
        }
    }
}
