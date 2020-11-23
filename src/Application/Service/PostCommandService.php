<?php

namespace App\Application\Service;

use App\Application\Command\Post\CreatePostCommand;
use App\Application\Command\Post\DeletePostCommand;
use App\Application\Command\Post\UpdatePostCommand;
use App\Domain\Collections\ParameterBag;
use App\Domain\ValueObject\PostContent;
use App\Domain\ValueObject\PostId;
use App\Domain\ValueObject\PostTitle;
use Symfony\Component\Messenger\MessageBusInterface;

class PostCommandService
{
    private TaskManager $taskManager;
    private MessageBusInterface $messageBus;

    public function __construct(TaskManager $taskManager, MessageBusInterface $messageBus)
    {
        $this->taskManager = $taskManager;
        $this->messageBus = $messageBus;
    }

    public function sendCreatePostCommand(PostTitle $postTitle, PostContent $postContent): ParameterBag
    {
        $refId = $this->taskManager->create();

        $accessKey = TokenService::generateRandomBytes();

        $this->messageBus->dispatch(new CreatePostCommand(
            $postTitle,
            $postContent,
            $refId,
            $accessKey
        ));

        return new ParameterBag([
            'reference_id' => $refId->toString(),
            'access_key' => $accessKey
        ]);
    }

    public function sendUpdatePostCommand(PostId $postId, PostTitle $postTitle, PostContent $postContent, string $accessKey)
    {
        $refId = $this->taskManager->create();

        $this->messageBus->dispatch(new UpdatePostCommand(
            $postId,
            $postTitle,
            $postContent,
            $refId,
            $accessKey
        ));

        return new ParameterBag([
            'reference_id' => $refId->toString(),
        ]);
    }

    public function sendDeletePostCommand(PostId $postId, string $accessKey)
    {
        $refId = $this->taskManager->create();

        $this->messageBus->dispatch(new DeletePostCommand(
            $postId,
            $refId,
            $accessKey
        ));

        return new ParameterBag([
            'reference_id' => $refId->toString(),
        ]);
    }
}
