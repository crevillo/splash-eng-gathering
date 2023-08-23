<?php

namespace App\Players\Dto;

use App\Players\Validator\ValidPlayer;
use Symfony\Component\Serializer\Annotation\SerializedName;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ValidPlayer()
 */
class PlayerInput
{
    public function __construct(
        /**
         * @Assert\NotBlank()
         */
        public readonly string $name,
        /**
         * @Assert\NotBlank()
         * @Assert\GreaterThanOrEqual(18)
         * @Assert\LessThan(100)
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
