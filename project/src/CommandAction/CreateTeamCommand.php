<?php

namespace App\CommandAction;

use App\Entity\Team;

class CreateTeamCommand
{
    public function __construct(
        public readonly Team $team
    ) {
    }

}
