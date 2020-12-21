<?php

namespace App\Validators\Auth;

use App\Domain\Repositories\Interfaces\UsersRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueEmailValidator extends ConstraintValidator
{
    private UsersRepositoryInterface $usersRepo;


    public function __construct(UsersRepositoryInterface $userRepository)
    {
        $this->usersRepo = $userRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        if ($this->usersRepo->existsByEmail($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
