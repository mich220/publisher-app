<?php

namespace App\Application\CommandHandler;

use App\Application\Command\Post\UpdatePostCommand;
use App\Application\Exception\PostNotFoundException;
use App\Application\Mapper\PostAggregateMapper;
use App\Application\Message\Event\Domain\PostTaskFailed;
use App\Application\Message\Event\Domain\PostUpdated;
use App\Application\Service\TransactionManager;
use App\Domain\Entity\Post;
use App\Domain\Policy\PostPolicy;
use App\Domain\Repository\PostAggregateRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class UpdatePostHandler implements MessageHandlerInterface
{
    private PostAggregateRepository $postRepository;
    private TransactionManager $transactionManager;
    private MessageBusInterface $eventBus;
    private LoggerInterface $logger;

    public function __construct(PostAggregateRepository $postRepository, MessageBusInterface $eventBus, LoggerInterface $logger)
    {
        $this->postRepository = $postRepository;
        $this->eventBus = $eventBus;
        $this->logger = $logger;
    }

    public function __invoke(UpdatePostCommand $command)
    {
        $postId = $command->getPostId();
        try {
            if (!$this->postRepository->exists($postId)) {
                $this->logger->alert(sprintf('Post not found, id: %s', $command->getPostId()->toString()));
                throw new PostNotFoundException(sprintf('Post not found, id: %s', $command->getPostId()->toString()));
            }
            $post = PostAggregateMapper::mapDbResultToAggregate(
                $this->postRepository->load($postId)
            );

            $post->update(Post::create(
                $postId,
                $command->getPostTitle(),
                $command->getPostContent(),
                $command->getAccessKey()
            ), new PostPolicy());
            $this->postRepository->update($post);

            $this->eventBus->dispatch(new PostUpdated($postId, $command->getTaskId()));
        } catch (\Exception $exception) {
            $this->logger->error(sprintf('Error while handling command: %s, error message: %s ', UpdatePostCommand::class, $exception->getMessage()));
            $this->eventBus->dispatch(new PostTaskFailed($command->getTaskId(), $exception->getCode()));
        }
    }
}
