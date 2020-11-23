<?php


namespace App\Infrastructure\Messenger;

use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

class RoutingMiddleware implements MiddlewareInterface
{

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $routingKey = $this->getMessageRoutingKey($envelope->getMessage());
        $envelope = $envelope->with(new AmqpStamp($routingKey, AMQP_NOPARAM, []));

        return $stack->next()->handle($envelope, $stack);
    }

    private function getMessageRoutingKey($message): string
    {
        $parts = array_reverse(explode('\\', (string)get_class($message)));

        return sprintf("%s.%s", $parts[1], $parts[0]);
    }
}