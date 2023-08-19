<?php

namespace App\Players\Message\Command;

use App\Players\Entity\Player;

class CreatePlayerCommand
{
    public function __construct(
        public readonly Player $player
    ) {
    }
}
