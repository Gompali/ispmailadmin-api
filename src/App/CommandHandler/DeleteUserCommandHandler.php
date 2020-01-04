<?php

declare(strict_types=1);

namespace App\App\CommandHandler;

use App\App\Command\DeleteUserCommand;
use App\Common\Exception\BadRequestException;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\VirtualUsers;

class DeleteUserCommandHandler
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(DeleteUserCommand $command)
    {
        $user = $this->userRepository->findOneBy([
            'email' => $command->getEmail(),
        ]);

        if (!$user instanceof VirtualUsers) {
            throw new BadRequestException('User not found');
        }

        $this->userRepository->remove($user);
    }
}
