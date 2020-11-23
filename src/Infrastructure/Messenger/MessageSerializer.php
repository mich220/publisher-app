<?php

namespace App\Infrastructure\Messenger;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\BusNameStamp;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class MessageSerializer implements SerializerInterface
{
    private string $targetBus;
    private string $commandsNamespace;

    public function __construct(string $commandsNamespace, string $targetBus)
    {
        $this->targetBus = $targetBus;
        $this->commandsNamespace = $commandsNamespace;
    }

    public function decode(array $encodedEnvelope): Envelope
    {
        $body = json_decode($encodedEnvelope['body'], true);
        $class = $body['class_path'];
        $message = $class::deserialize($body['payload']);

        $envelope = new Envelope($message);
        $envelope = $envelope->with(new ReceivedStamp(''));
        $envelope = $envelope->with(new BusNameStamp($this->targetBus));

        return $envelope;
    }

    public function encode(Envelope $envelope): array
    {
        $message = $envelope->getMessage();
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $body = [
            'payload' => $message->serialize(),
            'class_path' => (string)(get_class($message)),
        ];

        return [
            'body' => json_encode($body),
            'headers' => $headers,
        ];
    }
}