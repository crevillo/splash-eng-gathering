<?php

namespace App\Teams\Message\Command;

use App\Teams\Entity\Team;

class CreateTeamCommand
{
    public function __construct(
        public readonly Team $team
    ) {
    }

}
