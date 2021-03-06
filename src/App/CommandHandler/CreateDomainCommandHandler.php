<?php

declare(strict_types=1);

namespace App\App\CommandHandler;

use App\App\Command\CreateDomainCommand;
use App\Domain\Repository\DomainRepositoryInterface;
use App\Domain\VirtualDomains;
use Ramsey\Uuid\Uuid;

class CreateDomainCommandHandler
{
    /** @var DomainRepositoryInterface */
    private $domainRepository;

    public function __construct(DomainRepositoryInterface $domainRepository)
    {
        $this->domainRepository = $domainRepository;
    }

    public function __invoke(CreateDomainCommand $command): void
    {
        $domain = $this->domainRepository->findOneBy(['name' => $command->getDomain()]);

        if (!$domain instanceof VirtualDomains) {
            $domain = new VirtualDomains(
                Uuid::uuid4()->toString(),
                $command->getDomain()
            );

            $this->domainRepository->save($domain);
        }
    }
}
