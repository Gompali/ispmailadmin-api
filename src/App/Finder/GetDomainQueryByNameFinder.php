<?php

declare(strict_types=1);


namespace App\App\Finder;


use App\App\Query\GetDomainQueryByName;
use App\Domain\Repository\DomainRepositoryInterface;

class GetDomainQueryByNameFinder
{
    /**
     * @var DomainRepositoryInterface
     */
    private $domainRepository;

    public function __construct(DomainRepositoryInterface $domainRepository)
    {
        $this->domainRepository = $domainRepository;
    }

    public function __invoke(GetDomainQueryByName $query)
    {
        return $this->domainRepository->findOneBy([
            'name' => $query->getDomainName()
        ]);
    }
}