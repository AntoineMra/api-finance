<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
final class UniqueMonthBudgetValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        // TODO: Check if Month is Unique
        if (!array_diff(['description', 'price'], $value)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
