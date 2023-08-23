<?php

namespace App\Players\Validator;

use App\Players\Dto\PlayerInput;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidPlayerValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /** @var PlayerInput $value */

        if ($value->age > 50 && $value->salary > 2000000) {
            throw new BadRequestHttpException();
        }
    }
}
