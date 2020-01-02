<?php

declare(strict_types=1);

namespace App\App\CommandHandler;

use App\App\Command\DeleteDomainCommand;
use App\Domain\Repository\DomainRepositoryInterface;

class DeleteDomainCommandHandler
{
    /** @var DomainRepositoryInterface */
    private $domainRepository;

    public function __construct(DomainRepositoryInterface $domainRepository)
    {
        $this->domainRepository = $domainRepository;
    }

    public function __invoke(DeleteDomainCommand $command)
    {
        $domain = $command->getDomain();
        $this->domainRepository->findOneBy(['name' => $domain]);
        $this->domainRepository->remove($domain);
    }
}
