<?php

declare(strict_types=1);

namespace App\App\CommandHandler;

use App\App\Command\CreateUserCommand;
use App\Common\Exception\BadRequestException;
use App\Domain\Repository\DomainRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\VirtualUsers;

class CreateUserCommandHandler
{
    /** @var UserRepositoryInterface */
    private $userRepository;
    /**
     * @var DomainRepositoryInterface
     */
    private $domainRepository;

    public function __construct(
        DomainRepositoryInterface $domainRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->domainRepository = $domainRepository;
        $this->userRepository = $userRepository;
    }

    public function __invoke(CreateUserCommand $command): void
    {
        $user = new VirtualUsers(
            $command->getId(),
            $command->getEmail(),
            '{BLF-CRYPT}'.password_hash($command->getPassword(),
                PASSWORD_BCRYPT),
            $command->getQuota()
        );

        $domain = $this->domainRepository->findOneBy([
            'name' => $command->getDomain(),
        ]);

        if (!$domain) {
            throw new BadRequestException('The domain name does not exists in the database');
        }

        $user->setVirtualDomain($domain);

        $this->userRepository->save($user);
    }
}
