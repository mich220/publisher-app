<?php

namespace App\Application\Exception;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        if ($exception instanceof ValidationException) {
            $event->setResponse(new JsonResponse($exception->getValidationErrors(), 400));
        } else if ($exception instanceof NotFoundHttpException) {
            $event->setResponse(new JsonResponse('not found', 404));
        }
        // todo handle other exceptions
    }

    private function createResponse(array $data, int $status)
    {
        return new JsonResponse($data, $status);
    }

    public static function getSubscribedEvents(): iterable
    {
        yield KernelEvents::EXCEPTION => 'onKernelException';
    }
}
