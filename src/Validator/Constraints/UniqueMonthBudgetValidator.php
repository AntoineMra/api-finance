<?php

namespace App\Validator\Constraints;

use App\Repository\BudgetRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use UnexpectedValueException;

final class UniqueMonthBudgetValidator extends ConstraintValidator
{
    public function __construct(private readonly BudgetRepository $budgetRepository)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueMonthBudget) {
            throw new UnexpectedTypeException($constraint, UniqueMonthBudget::class);
        }

        if ($value === null) {
            throw new UnexpectedValueException('The date value should not be null for budgets', null);
        }

        if (!$value instanceof \DateTime) {
            throw new UnexpectedValueException($value, \DateTime::class);
        }

        $budgets = $this->budgetRepository->findAll();

        foreach ($budgets as $budget) {
            if ($budget->getFormatedDate() === $value->format('m/y')) {
                $throwError = true;
            }
        }

        if ($throwError ?? false) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
