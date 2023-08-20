<?php

namespace App\Players\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ValidPlayer extends Constraint
{
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
