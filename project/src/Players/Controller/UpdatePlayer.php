<?php

namespace App\Players\Controller;

use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\Request;

class UpdatePlayer
{
    public function __construct(
        private readonly CommandBus $bus
    ) {
    }

    public function __invoke(Request $request)
    {

    }
}
