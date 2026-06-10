<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class ValidProductNameValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        /* @var ValidProductName $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        foreach ($constraint->forbiddenWords as $forbiddenWord) {
            if (str_contains(strtolower($value), $forbiddenWord)) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ value }}', $value)
                    ->addViolation();
            }
        }
    }
}
