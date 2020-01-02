<?php

declare(strict_types=1);

namespace App\App\CommandHandler;

use App\App\Command\CreateAliasCommand;
use App\Domain\Repository\AliasRepositoryInterface;
use App\Domain\VirtualAliases;
use Ramsey\Uuid\Uuid;

class CreateAliasCommandHandler
{
    /**
     * @var AliasRepositoryInterface
     */
    private $aliasRepository;

    public function __construct(AliasRepositoryInterface $aliasRepository)
    {
        $this->aliasRepository = $aliasRepository;
    }

    public function __invoke(CreateAliasCommand $command)
    {
        $alias = new VirtualAliases(
            Uuid::uuid4()->toString(),
            $command->getSource(),
            $command->getDestination()
        );

        $this->aliasRepository->save($alias);
    }
}
