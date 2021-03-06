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

        if ($command->getQuota() !== $dbUser->getQuota()) {
            $dbUser->setQuota($command->getQuota());
        }

        if ($command->getPassword()) {
            if (strlen((string) $command->getPassword()) >= 12) {
                $hash = substr($dbUser->getPassword(), 11);
                if(!password_verify($command->getPassword(), $hash)){
                    $newPassword = password_hash($command->getPassword(), PASSWORD_BCRYPT);
                    $dbUser->setPassword('{BLF-CRYPT}'.$newPassword);
                    $hasChange = true;
                }
            }
        }

        $this->userRepository->save($dbUser);
    }
}
