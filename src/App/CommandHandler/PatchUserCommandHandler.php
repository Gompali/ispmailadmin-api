<?php

declare(strict_types=1);

namespace App\App\CommandHandler;

use App\App\Command\PatchUserCommand;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\VirtualUsers;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PatchUserCommandHandler
{
    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function __invoke(PatchUserCommand $command)
    {
        /** @var VirtualUsers $dbUser */
        $dbUser = $this->userRepository->find($command->getId());
        $hasChange = false;

        if ($command->getQuota() !== $dbUser->getQuota()) {
            $dbUser->setQuota($command->getQuota());
            $hasChange = true;
        }

        if ($command->getPassword()) {
            if (strlen((string) $command->getPassword()) >= 12) {
                if (false === $this->passwordEncoder->isPasswordValid($dbUser, $command->getPassword())) {
                    $newPassword = $this->passwordEncoder->encodePassword($command->getPassword());
                    $dbUser->setPassword($newPassword);
                    $hasChange = true;
                }
            }
        }

        if ($hasChange) {
            $this->userRepository->save($dbUser);
        }
    }
}
