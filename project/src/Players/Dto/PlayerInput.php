<?php

namespace App\Players\Dto;

use Symfony\Component\Serializer\Annotation\SerializedName;

class PlayerInput
{
    public function __construct(
        public readonly string $name,
        public readonly int $age,
        public readonly int $salary,
        /**
         * @var TeamInput
         * @SerializedName("team")
         */
        public readonly TeamInput $teamInput
    ) {
    }
}
