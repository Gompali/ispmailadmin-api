<?php

declare(strict_types=1);

namespace App\App\CommandHandler;

use App\App\Command\DeleteUserCommand;
use App\Domain\Repository\UserRepositoryInterface;

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
        $this->userRepository->remove($command->getEmail());
    }
}
