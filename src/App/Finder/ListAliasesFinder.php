<?php

declare(strict_types=1);


namespace App\App\Finder;


use App\App\Query\ListAliasesQuery;
use App\Domain\Repository\AliasRepositoryInterface;

class ListAliasesFinder
{
    /** @var AliasRepositoryInterface */
    private $aliasRepository;

    public function __construct(AliasRepositoryInterface $aliasRepository)
    {
        $this->aliasRepository = $aliasRepository;
    }
    public function __invoke(ListAliasesQuery $query)
    {
        if($query === null){
            throw new \LogicException('Empty query');
        }

        return $this->aliasRepository->findAll();
    }
}