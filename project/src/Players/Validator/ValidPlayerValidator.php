<?php

namespace App\Players\Validator;

use App\Players\Dto\PlayerInput;
use App\Teams\Repository\TeamsRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidPlayerValidator extends ConstraintValidator
{
    public function __construct(
        private readonly TeamsRepository $teamsRepository
    ) {
    }

    public function validate($value, Constraint $constraint)
    {
        /** @var PlayerInput $value */

        if ($value->age > 50 && $value->salary > 2000000) {
            throw new BadRequestHttpException();
        }

        if ($team = $this->teamsRepository->findOneBy(['name' => $value->teamInput->name])) {
            $underOr30 = $moreThan30 = 0;
            foreach ($team->getPlayers() as $player) {
                if ($player->getAge() <= 30) {
                    $underOr30++;
                } else {
                    $moreThan30++;
                }
            }

            if ($underOr30 >=6 && $value->age <= 30) {
                throw new BadRequestHttpException("Team has already 6 players under 30");
            }

            if ($moreThan30 >=6 && $value->age > 30) {
                throw new BadRequestHttpException("Team has already 6 players older than 30");
            }
        }
    }
}
