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

    public function __invoke(PatchUserCommand $command): void
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
                $hash = substr($dbUser->getPassword(),11,strlen($command->getPassword()));
                if(!password_verify($command->getPassword(), $hash)){
                    $newPassword = password_hash($command->getPassword(), PASSWORD_BCRYPT);
                    $dbUser->setPassword('{BLF-CRYPT}'.$newPassword);
                    $hasChange = true;
                }
            }
        }

        if ($hasChange) {
            $this->userRepository->save($dbUser);
        }
    }
}
