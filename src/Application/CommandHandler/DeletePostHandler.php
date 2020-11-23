<?php

namespace App\Application\CommandHandler;

use App\Application\Command\Post\DeletePostCommand;
use App\Application\Exception\PostNotFoundException;
use App\Application\Mapper\PostAggregateMapper;
use App\Application\Message\Event\Domain\PostDeleted;
use App\Application\Message\Event\Domain\PostTaskFailed;
use App\Application\Service\TransactionManager;
use App\Domain\Repository\PostAggregateRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class DeletePostHandler implements MessageHandlerInterface
{
    private PostAggregateRepository $postRepository;
    private MessageBusInterface $eventBus;
    private TransactionManager $transactionManager;
    private LoggerInterface $logger;

    public function __construct(
        PostAggregateRepository $postRepository,
        MessageBusInterface $eventBus,
        TransactionManager $transactionManager,
        LoggerInterface $logger
    )
    {
        $this->postRepository = $postRepository;
        $this->transactionManager = $transactionManager;
        $this->eventBus = $eventBus;
        $this->logger = $logger;
    }

    public function __invoke(DeletePostCommand $command)
    {
        $postId = $command->getPostId();
        $this->transactionManager->begin();
        try {
            if (!$this->postRepository->exists($postId)) {
                throw new PostNotFoundException(sprintf('Post with id: %s not found', $postId->toString()));
            }
            $post = PostAggregateMapper::mapDbResultToAggregate(
                $this->postRepository->load($postId)
            );
            $this->postRepository->delete($postId);

            $this->transactionManager->commit();
            $this->eventBus->dispatch(new PostDeleted($command->getPostId(), $command->getTaskId()));

        }
        catch (\Exception $exception) {
            $this->logger->error(sprintf('Error while handling command: %s, error message: %s ', DeletePostCommand::class, $exception->getMessage()));
            $this->transactionManager->rollback();
            $this->eventBus->dispatch(new PostTaskFailed($command->getTaskId(), $exception->getCode()));
        }
    }
}
