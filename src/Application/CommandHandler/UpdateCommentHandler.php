<?php

namespace App\Application\CommandHandler;

use App\Application\Command\Comment\UpdateCommentCommand;
use App\Application\Exception\PostNotFoundException;
use App\Application\Mapper\PostAggregateMapper;
use App\Application\Message\Event\Domain\CommentTaskFailed;
use App\Application\Message\Event\Domain\CommentUpdated;
use App\Application\Service\TransactionManager;
use App\Domain\Entity\Comment;
use App\Domain\Policy\CommentPolicy;
use App\Domain\Repository\PostAggregateRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class UpdateCommentHandler implements MessageHandlerInterface
{
    private PostAggregateRepository $postRepository;
    private TransactionManager $transactionManager;
    private MessageBusInterface $messageBus;
    private LoggerInterface $logger;

    public function __construct(
        PostAggregateRepository $postRepository,
        MessageBusInterface $eventBus,
        LoggerInterface $logger
    )
    {
        $this->postRepository = $postRepository;
        $this->messageBus = $eventBus;
        $this->logger = $logger;
    }

    public function __invoke(UpdateCommentCommand $command)
    {
        $postId = $command->getPostId();
        try {
            if (!$this->postRepository->exists($command->getPostId())) {
                $this->logger->alert(sprintf('Post not found, id: %s', $command->getPostId()->toString()));
                throw new PostNotFoundException(sprintf('Post not found, id: %s', $command->getPostId()->toString()));
            }
            $post = PostAggregateMapper::mapDbResultToAggregate(
                $this->postRepository->load($postId, true)
            );

            $comment = Comment::create($command->getCommentId(), $command->getPostId(), $command->getCommentContent(), $command->getAccessKey());

            $post->updateComment($comment, new CommentPolicy());
            $this->postRepository->update($post);

            $this->messageBus->dispatch(new CommentUpdated($command->getPostId(), $command->getCommentId(), $command->getTaskId()));
        } catch (\Exception $exception) {
            $this->logger->error(sprintf('Error while handling command: %s, error: %s', UpdateCommentCommand::class, $exception->getMessage()));
            $this->messageBus->dispatch(new CommentTaskFailed($command->getTaskId(), $exception->getCode()));
        }
    }
}
