<?php

namespace App\Application\EventSubscriber;

use App\Application\Message\Event\Domain\PostCreated;
use App\Application\Message\Event\Domain\PostDeleted;
use App\Application\Message\Event\Domain\PostTaskFailed;
use App\Application\Message\Event\Domain\PostUpdated;
use App\Application\Service\TaskManager;
use App\Domain\Cache\PostCacheRepository;
use App\Domain\ValueObject\PostId;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

class PostEventSubscriber implements MessageSubscriberInterface
{
    private TaskManager $taskManager;
    private LoggerInterface $logger;

    public function __construct(TaskManager $taskManager, LoggerInterface $logger)
    {
        $this->taskManager = $taskManager;
        $this->logger = $logger;
    }

    public function created(PostCreated $event)
    {
        $this->taskManager->finnish($event->getTaskId(), $this->getRef($event->getPostId()->toString()));
    }

    public function updated(PostUpdated $event)
    {
        $this->taskManager->finnish($event->getTaskId(), $this->getRef($event->getPostId()->toString()));
    }

    public function deleted(PostDeleted $event)
    {
        $this->taskManager->finnish($event->getTaskId(), $this->getRef(''));
    }

    public function failed(PostTaskFailed $event)
    {
        $this->taskManager->fail($event->getTaskId(), $event->getCode());
    }

    private function getRef(string $url)
    {
        return [
            'redirect_url' => '/api/posts/' . $url
        ];
    }

    public static function getHandledMessages(): iterable
    {
        yield PostCreated::class => ['method' => 'created'];
        yield PostUpdated::class => ['method' => 'updated'];
        yield PostDeleted::class => ['method' => 'deleted'];

        yield PostTaskFailed::class => ['method' => 'failed'];
    }
}

