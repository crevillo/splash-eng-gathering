<?php

namespace App\CommandAction;

use App\Entity\Player;

class CreatePlayerCommand
{
    public function __construct(
        public readonly Player $player
    ) {
    }
}
