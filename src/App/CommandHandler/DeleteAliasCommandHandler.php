<?php

declare(strict_types=1);

namespace App\App\CommandHandler;

use App\App\Command\DeleteAliasCommand;
use App\Domain\Repository\AliasRepositoryInterface;

class DeleteAliasCommandHandler
{
    /** @var AliasRepositoryInterface */
    private $repository;

    public function __construct(AliasRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(DeleteAliasCommand $command)
    {
        $alias = $this->repository->findOneBy([
            'source' => $command->getSource(),
            'destination' => $command->getDestination(),
        ]);

        $this->repository->remove($alias);
    }
}
