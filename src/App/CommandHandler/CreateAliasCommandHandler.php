<?php

declare(strict_types=1);

namespace App\App\CommandHandler;

use App\App\Command\CreateAliasCommand;
use App\Domain\Repository\AliasRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\VirtualAliases;
use App\Domain\VirtualUsers;
use Ramsey\Uuid\Uuid;

class CreateAliasCommandHandler
{
    /**
     * @var AliasRepositoryInterface
     */
    private $aliasRepository;
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    public function __construct(
        AliasRepositoryInterface $aliasRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->aliasRepository = $aliasRepository;
        $this->userRepository = $userRepository;
    }

    public function __invoke(CreateAliasCommand $command)
    {
        $destinationUser = $this->userRepository->findOneBy(['email' => $command->getDestination()]);
        /** @var VirtualUsers $destinationUser */
        $domain = $destinationUser->getVirtualDomain();

        $alias = new VirtualAliases(
            Uuid::uuid4()->toString(),
            $command->getSource(),
            $command->getDestination()
        );

        $alias->setDomain($domain);

        $this->aliasRepository->save($alias);
    }
}
