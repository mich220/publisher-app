<?php

namespace App\Application\CommandHandler;

use App\Application\Command\Post\CreatePostCommand;
use App\Application\Mapper\PostAggregateMapper;
use App\Application\Message\Event\Domain\PostCreated;
use App\Application\Message\Event\Domain\PostTaskFailed;
use App\Application\Service\TokenService;
use App\Domain\Aggregates\PostAggregate;
use App\Domain\Cache\PostCacheRepository;
use App\Domain\Collections\CommentsCollection;
use App\Domain\Entity\Post;
use App\Domain\Repository\PostAggregateRepository;
use App\Domain\ValueObject\PostId;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CreatePostHandler implements MessageHandlerInterface
{
    private PostAggregateRepository $postRepository;
    private PostAggregateRepository $postCacheRepository;
    private MessageBusInterface $eventBus;
    private LoggerInterface $logger;

    public function __construct(
        PostAggregateRepository $postRepository,
        PostAggregateRepository $postCacheRepository,
        MessageBusInterface $eventBus,
        LoggerInterface $logger
    )
    {
        $this->postRepository = $postRepository;
        $this->postCacheRepository = $postCacheRepository;
        $this->eventBus = $eventBus;
        $this->logger = $logger;
    }

    public function __invoke(CreatePostCommand $command)
    {
        try {
            $post = Post::create(
                null,
                $command->getPostTitle(),
                $command->getPostContent(),
                $command->getAccessKey()
            );

            $post = new PostAggregate($post, new CommentsCollection());
            $postId = $this->postRepository->save($post);

            // @todo set id in repo?
            $post = PostAggregateMapper::mapDbResultToDTO($this->postRepository->load(new PostId($postId)));
            $this->eventBus->dispatch(new PostCreated(
                new PostId($postId),
                $command->getTaskId()
            ));

            $this->postCacheRepository->set($post->getPost());
        } catch (\Exception $exception) {
            $this->logger->error(sprintf('Error while handling command: %s, error message: %s ', CreatePostCommand::class, $exception->getMessage()));
            $this->eventBus->dispatch(new PostTaskFailed($command->getTaskId(), $exception->getCode()));
        }
    }
}
