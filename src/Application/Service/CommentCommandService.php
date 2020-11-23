<?php

namespace App\Application\Service;

use App\Application\Command\Comment\CreateCommentCommand;
use App\Application\Command\Comment\DeleteCommentCommand;
use App\Application\Command\Comment\UpdateCommentCommand;
use App\Domain\Collections\ParameterBag;
use App\Domain\ValueObject\CommentContent;
use App\Domain\ValueObject\CommentId;
use App\Domain\ValueObject\PostId;
use Symfony\Component\Messenger\MessageBusInterface;

class CommentCommandService
{
    private TaskManager $taskManager;
    private MessageBusInterface $messageBus;

    public function __construct(TaskManager $taskManager, MessageBusInterface $messageBus)
    {
        $this->taskManager = $taskManager;
        $this->messageBus = $messageBus;
    }

    public function sendCreateCommentCommand(PostId $postId, CommentContent $commentContent): ParameterBag
    {
        $refId = $this->taskManager->create();
        $accessKey = TokenService::generateRandomBytes();

        $this->messageBus->dispatch(new CreateCommentCommand(
            $postId,
            $commentContent,
            $refId,
            $accessKey
        ));

        return new ParameterBag([
            'reference_id' => $refId->toString(),
            'access_key' => $accessKey
        ]);
    }

    public function sendUpdateCommentCommand(PostId $postId, CommentId $commentId, CommentContent $commentContent, string $commentAccessKey)
    {
        $refId = $this->taskManager->create();

        $this->messageBus->dispatch(new UpdateCommentCommand(
            $postId,
            $commentId,
            $commentContent,
            $refId,
            $commentAccessKey
        ));

        return new ParameterBag([
            'reference_id' => $refId->toString(),
        ]);
    }

    public function sendDeleteCommentCommand(PostId $postId, CommentId $commentId, string $commentAccessKey)
    {
        $refId = $this->taskManager->create();

        $this->messageBus->dispatch(new DeleteCommentCommand(
            $postId,
            $commentId,
            $refId,
            $commentAccessKey
        ));

        return new ParameterBag([
            'reference_id' => $refId->toString(),
        ]);
    }
}