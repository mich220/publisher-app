<?php

namespace App\Application\CommandHandler;

use App\Application\Command\Comment\DeleteCommentCommand;
use App\Application\Exception\PostNotFoundException;
use App\Application\Mapper\PostAggregateMapper;
use App\Application\Message\Event\Domain\CommentDeleted;
use App\Application\Message\Event\Domain\CommentTaskFailed;
use App\Application\Service\TransactionManager;
use App\Domain\Entity\Comment;
use App\Domain\Policy\CommentPolicy;
use App\Domain\Repository\PostAggregateRepository;
use App\Domain\ValueObject\CommentContent;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class DeleteCommentHandler implements MessageHandlerInterface
{
    private PostAggregateRepository $postRepository;
    private TransactionManager $transactionManager;
    private MessageBusInterface $eventBus;
    private LoggerInterface $logger;

    public function __construct(
        PostAggregateRepository $postRepository,
        TransactionManager $transactionManager,
        MessageBusInterface $eventBus,
        LoggerInterface $logger
    )
    {
        $this->postRepository = $postRepository;
        $this->transactionManager = $transactionManager;
        $this->eventBus = $eventBus;
        $this->logger = $logger;
    }

    public function __invoke(DeleteCommentCommand $command)
    {
        $postId = $command->getPostId();
        $this->transactionManager->begin();
        try {
            if (!$this->postRepository->exists($postId)) {
                throw new PostNotFoundException(sprintf('Post not found, id: %s', $command->getPostId()->toString()));
            }
            $post = PostAggregateMapper::mapDbResultToAggregate(
                $this->postRepository->load($postId, true)
            );
            $comment = Comment::create(
                $command->getCommentId(),
                $command->getPostId(),
                new CommentContent(''),
                $command->getAccessKey()
            );

            // @todo archive and do update instead of delete?
            // @todo - by comment id?
            $post->archive($comment, new CommentPolicy());
            $this->postRepository->deleteComment($comment, $postId);

            $this->transactionManager->commit();
            $this->eventBus->dispatch(new CommentDeleted($command->getPostId(), $command->getCommentId(), $command->getTaskId()));
        } catch (\Exception $exception) {
            $this->logger->error(sprintf('Error while handling command: %s, error: %s', DeleteCommentCommand::class, $exception->getMessage()));
            $this->transactionManager->rollback();
            $this->eventBus->dispatch(new CommentTaskFailed($command->getTaskId(), $exception->getCode()));
        }
    }
}
