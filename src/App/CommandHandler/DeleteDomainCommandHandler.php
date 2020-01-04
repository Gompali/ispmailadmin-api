<?php

declare(strict_types=1);

namespace App\App\CommandHandler;

use App\App\Command\DeleteDomainCommand;
use App\Common\Exception\BadRequestException;
use App\Domain\Repository\DomainRepositoryInterface;
use App\Domain\VirtualDomains;

class DeleteDomainCommandHandler
{
    /** @var DomainRepositoryInterface */
    private $domainRepository;

    public function __construct(DomainRepositoryInterface $domainRepository)
    {
        $this->domainRepository = $domainRepository;
    }

    public function __invoke(DeleteDomainCommand $command): void
    {
        $domain = $command->getDomain();
        $dbDomain = $this->domainRepository->findOneBy(['name' => $domain]);
        if (!$dbDomain instanceof VirtualDomains) {
            throw new BadRequestException('Domain does not exists');
        }
        $this->domainRepository->remove($dbDomain);
    }
}
