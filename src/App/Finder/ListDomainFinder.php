<?php

declare(strict_types=1);


namespace App\App\Finder;


use App\App\Query\ListDomainQuery;
use App\Domain\Repository\DomainRepositoryInterface;

class ListDomainFinder
{
    /**
     * @var DomainRepositoryInterface
     */
    private $domainRepository;

    public function __construct(DomainRepositoryInterface $domainRepository)
    {
        $this->domainRepository = $domainRepository;
    }
    public function __invoke(ListDomainQuery $query)
    {
        if($query === null){
            throw new \LogicException('Empty query');
        }

        return $this->domainRepository->findAll();
    }
}