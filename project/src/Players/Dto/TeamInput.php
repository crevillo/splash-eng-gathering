<?php

namespace App\Players\Dto;

class TeamInput
{
    public function __construct(
        public readonly string $name
    ) {
    }
}
