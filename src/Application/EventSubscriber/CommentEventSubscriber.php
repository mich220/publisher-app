<?php

namespace App\Application\EventSubscriber;

use App\Application\Message\Event\Domain\CommentCreated;
use App\Application\Message\Event\Domain\CommentDeleted;
use App\Application\Message\Event\Domain\CommentTaskFailed;
use App\Application\Message\Event\Domain\CommentUpdated;
use App\Application\Service\TaskManager;
use App\Domain\ValueObject\CommentId;
use App\Domain\ValueObject\PostId;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

class CommentEventSubscriber implements MessageSubscriberInterface
{
    private TaskManager $taskManager;
    private LoggerInterface $logger;

    // @todo - do some more comment related stuff or merge with PostEventSubscriber
    public function __construct(TaskManager $taskManager, LoggerInterface $logger)
    {
        $this->taskManager = $taskManager;
        $this->logger = $logger;
    }

    public function created(CommentCreated $event)
    {
        $this->taskManager->finnish($event->getTaskId(), $this->getRef($event->getPostId(), $event->getCommentId()));
    }

    public function updated(CommentUpdated $event)
    {
        $this->taskManager->finnish($event->getTaskId(), $this->getRef($event->getPostId(), $event->getCommentId()));
    }

    public function deleted(CommentDeleted $event)
    {
        $this->taskManager->finnish($event->getTaskId(), $this->getRef($event->getPostId(), $event->getCommentId()));
    }

    public function failed(CommentTaskFailed $event)
    {
        $this->taskManager->fail($event->getTaskId(), $event->getCode());
    }

    private function getRef(PostId $postId, CommentId $commentId)
    {
        return [
            'redirect_url' => '/api/posts/' . $postId->toString() . '/comments/' . $commentId->toString()
        ];
    }

    public static function getHandledMessages(): iterable
    {
        yield CommentCreated::class => ['method' => 'created'];
        yield CommentUpdated::class => ['method' => 'updated'];
        yield CommentDeleted::class => ['method' => 'deleted'];

        yield CommentTaskFailed::class => ['method' => 'failed'];
    }
}

