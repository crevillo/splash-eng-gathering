<?php

namespace App\Players\Dto;

use Symfony\Component\Serializer\Annotation\SerializedName;

use Symfony\Component\Validator\Constraints as Assert;

class PlayerInput
{
    public function __construct(
        /**
         * @Assert\NotBlank()
         */
        public readonly string $name,
        /**
         * @Assert\NotBlank()
         */
        public readonly int $age,
        /**
         * @Assert\NotBlank()
         */
        public readonly int $salary,
        /**
         * @var TeamInput
         * @SerializedName("team")
         * @Assert\Valid
         */
        public readonly TeamInput $teamInput
    ) {
    }
}
