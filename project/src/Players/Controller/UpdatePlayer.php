<?php

namespace App\Players\Controller;

use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class UpdatePlayer
{
    use HandleTrait;

    public function __construct(
        MessageBusInterface $bus
    ) {
        $this->messageBus = $bus;
    }

    public function __invoke(Request $request)
    {

    }
}
