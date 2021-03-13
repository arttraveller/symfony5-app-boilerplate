<?php

namespace App\Auth\Presentation\Validators;

use App\User\Application\QueryServices\UsersQueryServiceInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueEmailValidator extends ConstraintValidator
{
    private UsersQueryServiceInterface $usersQueryService;

    public function __construct(UsersQueryServiceInterface $usersQueryService)
    {
        $this->usersQueryService = $usersQueryService;
    }

    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        if ($this->usersQueryService->existsByEmail($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
