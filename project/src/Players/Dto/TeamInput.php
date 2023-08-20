<?php

namespace App\Players\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class TeamInput
{
    public function __construct(
        /** @Assert\NotBlank  */
        public readonly string $name
    ) {
    }
}
