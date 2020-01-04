<?php

declare(strict_types=1);

namespace App\App\CommandHandler;

use App\App\Command\UpdateUserCommand;
use App\Common\Infra\Messenger\HandleTrait;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\VirtualUsers;
use Doctrine\ORM\EntityNotFoundException;

class UpdateUserCommandHandler
{
    use HandleTrait;

    /** @var UserRepositoryInterface */
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(UpdateUserCommand $command): void
    {
        $dbUser = $this->userRepository->findOneBy([
            'email' => $command->getEmail(), ]
        );

        if (!$dbUser instanceof VirtualUsers) {
            throw new EntityNotFoundException('No user with this email : '.$command->getEmail());
        }

        if (
            null !== $command->getPassword()
            && is_string($command->getPassword())
            && strlen($command->getPassword()) > 11
        ) {
            $plainPassword = $command->getPassword();
            $match = password_verify($plainPassword, substr($dbUser->getPassword(), 11));
            if (!$match) {
                $dbUser->setPassword(
                    '{BLF-CRYPT}'.password_hash($plainPassword, PASSWORD_BCRYPT)
                );
            }
        }

        if ($dbUser->getQuota() !== $command->getQuota()) {
            $dbUser->setQuota($command->getQuota());
        }

        $this->userRepository->save($dbUser);
    }
}
