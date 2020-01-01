<?php

declare(strict_types=1);

namespace App\Infra\Validator;


use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\VirtualUsers;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EmailUniqueConstraintValidator extends ConstraintValidator
{
    /** @var UserRepositoryInterface */
    private $UserRepository;

    public function __construct(UserRepositoryInterface $UserRepository)
    {
        $this->UserRepository = $UserRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        $user = $this->UserRepository->findOneBy([
            'email' => $value,
        ]);

        if ($user instanceof VirtualUsers) {
            /* @var EmailUniqueConstraint $constraint */
            $this->context->buildViolation($constraint->getMessage())
                ->setParameter('{{ email }}', $value)
                ->addViolation();
        }
    }
}
